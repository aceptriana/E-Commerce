<?php

namespace App\Controllers;

use App\Models\KeranjangModel;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;
use App\Models\PengirimanModel;
use App\Models\PembayaranModel;
use App\Models\ProdukModel;
use App\Models\UserModel;   

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class Checkout extends BaseController
{
    protected $keranjangModel;
    protected $pesananModel;
    protected $detailPesananModel;
    protected $userModel;
    protected $pembayaranModel;
    protected $rajaongkirKey;
    protected $midtransServerKey = 'SB-Mid-server-SWmyqt2R9RF8ILIhPwXBCRJF';
    protected $midtransClientKey = 'SB-Mid-client-eFde8jX8Q_awQxBE';
    protected $originCityId = 211; // ID kota asal (tetap)

    public function __construct()
    {
        $this->keranjangModel = new KeranjangModel();
        $this->pesananModel = new PesananModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->userModel = new \App\Models\UserModel();
        $this->pembayaranModel = new \App\Models\PembayaranModel(); // Initialize pembayaranModel here
        // Try multiple API keys for fallback
        $this->rajaongkirKey = getenv('RAJAONGKIR_API_KEY') ?: '8f22875183c8c65879ef1ed0615d3371';
        
        // Alternative API keys (you can add more valid keys here)
        $this->alternativeKeys = [
            '8f22875183c8c65879ef1ed0615d3371', // Current key
            // Add other valid keys here if available
        ];
        
        // Set Midtrans global config
        Config::$serverKey = $this->midtransServerKey;
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Method untuk update status pembayaran manual tanpa callback Midtrans
    public function manualUpdatePaymentStatus()
    {
        $order_id = $this->request->getPost('order_id');
        $status = $this->request->getPost('status'); // expected values: 'pending', 'berhasil', 'gagal', etc.

        if (!$order_id || !$status) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'order_id dan status harus diisi'
            ]);
        }

        // Cari pesanan berdasarkan external_id di pembayaran
        $pembayaran = $this->pembayaranModel->where('external_id', $order_id)->first();

        if (!$pembayaran) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pembayaran dengan order_id tersebut tidak ditemukan'
            ]);
        }

        // Update status menggunakan method baru di PesananModel
        $payment_data = [
            'status' => $status,
            'waktu_bayar' => date('Y-m-d H:i:s')
        ];

        $result = $this->pesananModel->updateOrderStatus(
            $pembayaran['pesanan_id'], 
            $status, 
            $payment_data
        );

        if (!$result) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui status pesanan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status pembayaran dan pesanan berhasil diperbarui'
        ]);
    }

    // Method untuk polling status pembayaran Midtrans secara berkala
    public function pollMidtransPaymentStatus()
    {
        // Ambil semua pembayaran dengan status pending
        $pendingPayments = $this->pembayaranModel->where('status', 'pending')->findAll();

        if (empty($pendingPayments)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Tidak ada pembayaran pending untuk diproses'
            ]);
        }

        $updatedCount = 0;
        foreach ($pendingPayments as $payment) {
            try {
                // Ambil status transaksi dari Midtrans menggunakan API Status
                $statusResponse = \Midtrans\Transaction::status($payment['external_id']);

                $transactionStatus = $statusResponse->transaction_status ?? null;
                $paymentType = $statusResponse->payment_type ?? null;
                $fraudStatus = $statusResponse->fraud_status ?? null;

                // Tentukan status internal berdasarkan status Midtrans
                $internalStatus = $this->determineOrderStatus($transactionStatus, $paymentType, $fraudStatus);

                if ($internalStatus !== $payment['status']) {
                    // Prepare payment data
                    $payment_data = [
                        'status' => $internalStatus,
                        'payment_type' => $paymentType,
                        'transaction_id' => $statusResponse->transaction_id ?? null,
                        'va_number' => $this->extractVaNumberFromNotification($statusResponse),
                        'status_code' => $statusResponse->status_code ?? null,
                        'status_message' => $statusResponse->status_message ?? null,
                        'waktu_bayar' => date('Y-m-d H:i:s', strtotime($statusResponse->transaction_time ?? 'now'))
                    ];

                    // Update status using centralized method
                    $result = $this->pesananModel->updateOrderStatus(
                        $payment['pesanan_id'],
                        $internalStatus,
                        $payment_data
                    );

                    if ($result) {
                        $updatedCount++;
                    } else {
                        log_message('error', 'Failed to update order status for ID: ' . $payment['pesanan_id']);
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Polling Midtrans Error for order ' . $payment['external_id'] . ': ' . $e->getMessage());
                continue;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Polling selesai, $updatedCount pembayaran diperbarui"
        ]);
    }

    private function extractVaNumberFromNotification($notif)
    {
        // Midtrans notification may have va_numbers array or other fields depending on payment type
        if (isset($notif->va_numbers) && is_array($notif->va_numbers) && count($notif->va_numbers) > 0) {
            return $notif->va_numbers[0]->va_number ?? null;
        }
        if (isset($notif->permata_va_number)) {
            return $notif->permata_va_number;
        }
        if (isset($notif->bill_key)) {
            return $notif->bill_key;
        }
        return null;
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user_id = session()->get('user_id');

        // Check if we're only checking out a subset of cart items
        $cart_ids_param = $this->request->getGet('cart_ids');
        $selected_cart_ids = null;
        if ($cart_ids_param) {
            $ids = array_filter(array_map('intval', explode(',', $cart_ids_param)));
            $cart_items = $this->keranjangModel->getCartWithProductsByIds($user_id, $ids);
            $selected_cart_ids = implode(',', array_column($cart_items, 'id'));
        } else {
            $cart_items = $this->keranjangModel->getCartWithProducts($user_id);
        }
        
        if (empty($cart_items)) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang belanja kosong');
        }

        $subtotal = 0;
        $total_weight = 0;
        foreach ($cart_items as $item) {
            $subtotal += $item['harga'] * $item['quantity'];
            // Gunakan berat default 1000 gram jika berat tidak tersedia
            $weight = isset($item['berat']) ? $item['berat'] : 1000;
            $total_weight += ($weight * $item['quantity']);
        }

        $data = [
            'cart_items' => $cart_items,
            'user' => $this->userModel->find(session()->get('user_id')),
            'subtotal' => $subtotal,
            'shipping_cost' => 0,
            'total' => $subtotal,
            'total_weight' => $total_weight,
            'midtrans_client_key' => $this->midtransClientKey
        ];

        // include selected cart ids in data so checkout form can post them
        $data['selected_cart_ids'] = $selected_cart_ids;

        return view('checkout/index', $data);
    }

    public function searchCity()
    {
        $query = $this->request->getGet('query');
        
        if (strlen($query) < 2) {
            return $this->response->setJSON([]);
        }
        
        // Try to get cities from API, fallback to static list if fails
        $apiResult = $this->tryRajaOngkirAPI($query);
        
        if ($apiResult !== false) {
            return $this->response->setJSON($apiResult);
        }
        
        // If API fails, use fallback cities
        log_message('info', 'Using fallback cities for query: ' . $query);
        return $this->response->setJSON($this->getFallbackCities($query));
    }

    private function tryRajaOngkirAPI($query)
    {
        $curl = \Config\Services::curlrequest();
        
        // Try different API endpoints and keys
        $endpoints = [
            'https://api.rajaongkir.com/starter/city',
            'https://pro.rajaongkir.com/api/city'
        ];
        
        $keys = array_merge([$this->rajaongkirKey], $this->alternativeKeys ?? []);
        
        foreach ($endpoints as $endpoint) {
            foreach ($keys as $key) {
                try {
                    log_message('debug', 'Trying endpoint: ' . $endpoint . ' with key: ' . substr($key, 0, 10) . '...');
                    
                    $response = $curl->request('GET', $endpoint, [
                        'headers' => ['key' => $key],
                        'timeout' => 15
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    
                    // Check if response is valid
                    if (isset($result['rajaongkir']['results'])) {
                        log_message('info', 'Successfully connected to RajaOngkir API');
                        return $this->processCityData($result, $query, $key);
                    } else {
                        log_message('warning', 'Invalid API response structure: ' . json_encode($result));
                    }
                    
                } catch (\Exception $e) {
                    log_message('warning', 'API call failed for ' . $endpoint . ': ' . $e->getMessage());
                    continue;
                }
            }
        }
        
        return false; // All attempts failed
    }

    private function processCityData($result, $query, $apiKey)
    {
        $cities = $result['rajaongkir']['results'] ?? [];
        
        // Get provinces for mapping
        $provinces = $this->getProvinces($apiKey);
        $provinceMap = [];
        foreach ($provinces as $province) {
            $provinceMap[$province['province_id']] = $province['province'];
        }
        
        // Filter and format cities
        $filtered = [];
        foreach ($cities as $city) {
            if (stripos($city['city_name'], $query) !== false || 
                stripos($city['type'], $query) !== false) {
                
                $filtered[] = [
                    'city_id' => $city['city_id'],
                    'city_name' => $city['city_name'],
                    'type' => $city['type'],
                    'province' => $provinceMap[$city['province_id']] ?? 'Unknown Province',
                    'province_id' => $city['province_id']
                ];
            }
        }
        
        // Sort and limit results
        usort($filtered, function($a, $b) {
            return strcasecmp($a['city_name'], $b['city_name']);
        });
        
        return array_slice($filtered, 0, 10);
    }

    private function getProvinces($apiKey)
    {
        $curl = \Config\Services::curlrequest();
        
        $endpoints = [
            'https://api.rajaongkir.com/starter/province',
            'https://pro.rajaongkir.com/api/province'
        ];
        
        foreach ($endpoints as $endpoint) {
            try {
                $response = $curl->request('GET', $endpoint, [
                    'headers' => ['key' => $apiKey],
                    'timeout' => 15
                ]);
                
                $result = json_decode($response->getBody(), true);
                
                if (isset($result['rajaongkir']['results'])) {
                    return $result['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        return []; // Return empty array if all attempts fail
    }

    private function getFallbackCities($query)
    {
        // Static list of major Indonesian cities as fallback
        $cities = [
            ['city_id' => 151, 'city_name' => 'Jakarta Pusat', 'type' => 'Kota', 'province' => 'DKI Jakarta', 'province_id' => 6],
            ['city_id' => 152, 'city_name' => 'Jakarta Utara', 'type' => 'Kota', 'province' => 'DKI Jakarta', 'province_id' => 6],
            ['city_id' => 153, 'city_name' => 'Jakarta Barat', 'type' => 'Kota', 'province' => 'DKI Jakarta', 'province_id' => 6],
            ['city_id' => 154, 'city_name' => 'Jakarta Selatan', 'type' => 'Kota', 'province' => 'DKI Jakarta', 'province_id' => 6],
            ['city_id' => 155, 'city_name' => 'Jakarta Timur', 'type' => 'Kota', 'province' => 'DKI Jakarta', 'province_id' => 6],
            ['city_id' => 22, 'city_name' => 'Bandung', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 23, 'city_name' => 'Bekasi', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 24, 'city_name' => 'Bogor', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 25, 'city_name' => 'Cimahi', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 26, 'city_name' => 'Cirebon', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 27, 'city_name' => 'Depok', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 28, 'city_name' => 'Sukabumi', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 29, 'city_name' => 'Tasikmalaya', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 30, 'city_name' => 'Banjar', 'type' => 'Kota', 'province' => 'Jawa Barat', 'province_id' => 9],
            ['city_id' => 55, 'city_name' => 'Surabaya', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 56, 'city_name' => 'Malang', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 57, 'city_name' => 'Sidoarjo', 'type' => 'Kabupaten', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 58, 'city_name' => 'Mojokerto', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 59, 'city_name' => 'Pasuruan', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 60, 'city_name' => 'Probolinggo', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 61, 'city_name' => 'Kediri', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 62, 'city_name' => 'Blitar', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 63, 'city_name' => 'Madiun', 'type' => 'Kota', 'province' => 'Jawa Timur', 'province_id' => 11],
            ['city_id' => 64, 'city_name' => 'Solo', 'type' => 'Kota', 'province' => 'Jawa Tengah', 'province_id' => 10],
            ['city_id' => 65, 'city_name' => 'Semarang', 'type' => 'Kota', 'province' => 'Jawa Tengah', 'province_id' => 10],
            ['city_id' => 66, 'city_name' => 'Yogyakarta', 'type' => 'Kota', 'province' => 'DI Yogyakarta', 'province_id' => 5],
            ['city_id' => 67, 'city_name' => 'Sleman', 'type' => 'Kabupaten', 'province' => 'DI Yogyakarta', 'province_id' => 5],
            ['city_id' => 68, 'city_name' => 'Bantul', 'type' => 'Kabupaten', 'province' => 'DI Yogyakarta', 'province_id' => 5],
            ['city_id' => 69, 'city_name' => 'Gunung Kidul', 'type' => 'Kabupaten', 'province' => 'DI Yogyakarta', 'province_id' => 5],
            ['city_id' => 70, 'city_name' => 'Kulon Progo', 'type' => 'Kabupaten', 'province' => 'DI Yogyakarta', 'province_id' => 5],
        ];
        
        // Filter cities based on query
        $filtered = [];
        foreach ($cities as $city) {
            if (stripos($city['city_name'], $query) !== false || 
                stripos($city['type'], $query) !== false ||
                stripos($city['province'], $query) !== false) {
                $filtered[] = $city;
            }
        }
        
        // Sort and limit results
        usort($filtered, function($a, $b) {
            return strcasecmp($a['city_name'], $b['city_name']);
        });
        
        return array_slice($filtered, 0, 10);
    }

    public function cekOngkir()
    {
        $origin = $this->originCityId;
        $destination = $this->request->getPost('destination');
        $weight = $this->request->getPost('weight');
        
        // Debug logging
        log_message('debug', 'CekOngkir - Origin: ' . $origin . ', Destination: ' . $destination . ', Weight: ' . $weight);
        
        // Pastikan berat minimal 1 gram
        if (!$weight || $weight < 1) {
            $weight = 1000; // Default 1kg
        }
        
        // Pastikan destination ada
        if (!$destination) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Destinasi tidak valid',
                'csrf_hash' => csrf_hash()
            ]);
        }
        
        // Try to get shipping costs from API
        $apiResult = $this->tryShippingCostAPI($origin, $destination, $weight);
        
        if ($apiResult !== false) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $apiResult,
                'csrf_hash' => csrf_hash()
            ]);
        }
        
        // If API fails, return fallback shipping options
        log_message('info', 'Using fallback shipping costs');
        return $this->response->setJSON([
            'success' => true,
            'data' => $this->getFallbackShippingCosts($destination, $weight),
            'csrf_hash' => csrf_hash()
        ]);
    }

    private function tryShippingCostAPI($origin, $destination, $weight)
    {
        $curl = \Config\Services::curlrequest();
        
        $endpoints = [
            'https://api.rajaongkir.com/starter/cost',
            'https://pro.rajaongkir.com/api/cost'
        ];
        
        $keys = array_merge([$this->rajaongkirKey], $this->alternativeKeys ?? []);
        $couriers = ['jne', 'pos', 'tiki'];
        $results = [];
        
        foreach ($endpoints as $endpoint) {
            foreach ($keys as $key) {
                foreach ($couriers as $courier) {
                    try {
                        log_message('debug', 'Trying shipping cost API: ' . $endpoint . ' with courier: ' . $courier);
                        
                        $response = $curl->request('POST', $endpoint, [
                            'headers' => [
                                'key' => $key,
                                'content-type' => 'application/x-www-form-urlencoded',
                            ],
                            'form_params' => [
                                'origin' => $origin,
                                'destination' => $destination,
                                'weight' => $weight,
                                'courier' => $courier,
                            ],
                            'timeout' => 15
                        ]);
                    
                        $result = json_decode($response->getBody(), true);
                        
                        if (isset($result['rajaongkir']['results'][0])) {
                            $results[] = $result['rajaongkir']['results'][0];
                            log_message('info', 'Successfully got shipping cost for ' . $courier);
                        }
                        
                    } catch (\Exception $e) {
                        log_message('warning', 'Shipping cost API failed for ' . $courier . ': ' . $e->getMessage());
                        continue;
                    }
                }
                
                // If we got some results, return them
                if (!empty($results)) {
                    return $results;
                }
            }
        }
        
        return false; // All attempts failed
    }

    private function getFallbackShippingCosts($destination, $weight)
    {
        // Fallback shipping costs based on destination and weight
        $baseCosts = [
            'jne' => [
                'code' => 'jne',
                'name' => 'Jalur Nugraha Ekakurir (JNE)',
                'costs' => [
                    [
                        'service' => 'OKE',
                        'description' => 'Ongkos Kirim Ekonomis',
                        'cost' => [
                            [
                                'value' => max(15000, $weight * 10), // Minimum 15k, 10 per gram
                                'etd' => '2-3',
                                'note' => 'Estimasi pengiriman 2-3 hari'
                            ]
                        ]
                    ],
                    [
                        'service' => 'REG',
                        'description' => 'Layanan Reguler',
                        'cost' => [
                            [
                                'value' => max(20000, $weight * 15), // Minimum 20k, 15 per gram
                                'etd' => '1-2',
                                'note' => 'Estimasi pengiriman 1-2 hari'
                            ]
                        ]
                    ]
                ]
            ],
            'pos' => [
                'code' => 'pos',
                'name' => 'POS Indonesia',
                'costs' => [
                    [
                        'service' => 'Paket Kilat Khusus',
                        'description' => 'Paket Kilat Khusus',
                        'cost' => [
                            [
                                'value' => max(18000, $weight * 12),
                                'etd' => '2-3',
                                'note' => 'Estimasi pengiriman 2-3 hari'
                            ]
                        ]
                    ]
                ]
            ],
            'tiki' => [
                'code' => 'tiki',
                'name' => 'Citra Van Titipan Kilat',
                'costs' => [
                    [
                        'service' => 'ECO',
                        'description' => 'Economy Service',
                        'cost' => [
                            [
                                'value' => max(16000, $weight * 11),
                                'etd' => '2-3',
                                'note' => 'Estimasi pengiriman 2-3 hari'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        return array_values($baseCosts);
    }

    public function process()
    {
        if (!$this->request->getPost()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        // Validasi input
        $rules = [
            'nama_lengkap' => 'required',
            'email' => 'required|valid_email',
            'no_telepon' => 'required',
            'alamat' => 'required',
            'destination' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'shipping_service' => 'required',
            'shipping_description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . implode(', ', $this->validator->getErrors())
            ]);
        }

        // Ambil data dari form
        $nama = $this->request->getPost('nama_lengkap');
        $email = $this->request->getPost('email');
        $no_telepon = $this->request->getPost('no_telepon');
        $alamat = $this->request->getPost('alamat');
        $destination = $this->request->getPost('destination');
        $shipping_cost = (int)$this->request->getPost('shipping_cost');
        $shipping_service = $this->request->getPost('shipping_service');
        $shipping_description = $this->request->getPost('shipping_description');

        // Hitung total
        $user_id = session()->get('user_id');
        $selected_cart_ids = $this->request->getPost('selected_cart_ids');
        $ids = [];
        if ($selected_cart_ids) {
            $ids = array_filter(array_map('intval', explode(',', $selected_cart_ids)));
            $cart_items = $this->keranjangModel->getCartWithProductsByIds($user_id, $ids);
        } else {
            $cart_items = $this->keranjangModel->getCartWithProducts($user_id);
        }
        if (empty($cart_items)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada item yang diproses. Pastikan produk masih tersedia.'
            ]);
        }

        $subtotal = 0;
        foreach ($cart_items as $item) {
            $subtotal += $item['harga'] * $item['quantity'];
        }
        $total = $subtotal + $shipping_cost;

        // Siapkan data untuk Midtrans
        $order_id = 'ORDER-' . time() . '-' . $user_id;
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $total
        ];

        $customer_details = [
            'first_name' => $nama,
            'last_name' => '',
            'email' => $email,
            'phone' => $no_telepon,
            'billing_address' => [
                'address' => $alamat
            ],
            'shipping_address' => [
                'address' => $alamat
            ]
        ];

        $item_details = [];
            foreach ($cart_items as $item) {
            $item_details[] = [
                'id' => $item['produk_id'],
                'price' => $item['harga'],
                'quantity' => $item['quantity'],
                'name' => $item['produk_nama']
            ];
        }

        // Tambahkan biaya pengiriman ke item details
        if ($shipping_cost > 0) {
            $item_details[] = [
                'id' => 'SHIPPING',
                'price' => $shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman - ' . $shipping_description
            ];
        }

        // Buat transaksi
        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details
        ];

        try {
            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($transaction);

            // Simpan data pesanan ke database
            $pesananData = [
                'user_id' => $user_id,
                'tanggal_pesanan' => date('Y-m-d H:i:s'),
                'status' => 'menunggu_pembayaran',
                'total' => $total,
                'alamat_pengiriman' => $alamat,
                'tanggal_estimasi_pengiriman' => $shipping_description,
                // 'no_resi' => null, // Optional, can be added later
            ];

            $pesanan_id = $this->pesananModel->insert($pesananData);

            // Simpan data pembayaran
            $pembayaranModel = new \App\Models\PembayaranModel();
            $pembayaranData = [
                'pesanan_id' => $pesanan_id,
                'status' => 'pending',
                'metode_pembayaran' => 'midtrans',
                'transaction_id' => null,
                'payment_type' => null,
                'va_number' => null,
                'status_code' => null,
                'status_message' => null,
                'total_bayar' => $total,
                'waktu_bayar' => date('Y-m-d H:i:s'),
                'external_id' => $order_id
            ];
            $pembayaran_id = $pembayaranModel->insert($pembayaranData);

            // Simpan detail pesanan
            foreach ($cart_items as $item) {
                $detailPesananData = [
                    'pesanan_id' => $pesanan_id,
                    'produk_id' => $item['produk_id'],
                    'harga_satuan' => $item['harga'],
                    'jumlah' => $item['quantity'],
                    // Removed 'subtotal' as it's not allowed in model
                ];
                $this->detailPesananModel->insert($detailPesananData);
            }

            // Hapus item yang diproses dari keranjang
            if (!empty($ids)) {
                $this->keranjangModel->whereIn('id', $ids)->where('user_id', $user_id)->delete();
            }

            // Simpan data pengiriman
            $pengirimanModel = new \App\Models\PengirimanModel();
            $pengirimanData = [
                'pesanan_id' => $pesanan_id,
                'jasa_pengiriman' => $shipping_service,
                'biaya_pengiriman' => $shipping_cost,
                'estimasi_pengiriman' => $shipping_description,
                'status_pengiriman' => 'pending'
            ];
            $pengirimanModel->insert($pengirimanData);

            // For testing/development, simulate payment success
            // Comment this section in production
            if (ENVIRONMENT === 'development') {
                $payment_data = [
                    'status' => 'berhasil',
                    'waktu_bayar' => date('Y-m-d H:i:s'),
                    'total_bayar' => $total,
                    'external_id' => $order_id
                ];
                
                $result = $this->pesananModel->updateOrderStatus(
                    $pesanan_id,
                    'diproses', // Match the enum value in pesanan table
                    $payment_data
                );

                if (!$result) {
                    throw new \Exception('Gagal memproses pesanan');
                }
            }

            // Return success response dengan snap token
            return $this->response->setJSON([
                'status' => 'success',
                'snap_token' => $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Midtrans Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    public function notification()
    {
        try {
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Tentukan status berdasarkan respons Midtrans
            $status = $this->determineOrderStatus($transaction, $type, $fraud);

            // Cari pesanan berdasarkan order_id
            $pembayaran = $this->pembayaranModel->where('external_id', $order_id)->first();
            if (!$pembayaran) {
                throw new \Exception('Pembayaran tidak ditemukan');
            }

            // Siapkan data pembayaran
            $payment_data = [
                'metode_pembayaran' => $type,
                'total_bayar' => $notif->gross_amount ?? null,
                'waktu_bayar' => date('Y-m-d H:i:s', strtotime($notif->transaction_time ?? 'now')),
                'transaction_id' => $notif->transaction_id ?? null,
                'payment_type' => $type,
                'va_number' => $this->extractVaNumberFromNotification($notif),
                'status_code' => $notif->status_code ?? null,
                'status_message' => $notif->status_message ?? null,
                'external_id' => $order_id
            ];

            // Update status menggunakan method baru di PesananModel
            $result = $this->pesananModel->updateOrderStatus(
                $pembayaran['pesanan_id'],
                $status,
                $payment_data
            );

            if (!$result) {
                throw new \Exception('Gagal memperbarui status pesanan');
            }

            // Jika pembayaran berhasil, bersihkan keranjang
            if ($status === 'berhasil') {
                $pesanan = $this->pesananModel->find($pembayaran['pesanan_id']);
                if ($pesanan) {
                    // Hapus hanya item yang terkait dengan pesanan ini dari keranjang
                    $orderDetails = $this->detailPesananModel->where('pesanan_id', $pesanan['id'])->findAll();
                    $productIds = array_column($orderDetails, 'produk_id');
                    if (!empty($productIds)) {
                        $this->keranjangModel->where('user_id', $pesanan['user_id'])->whereIn('produk_id', $productIds)->delete();
                    }
                }
            }

            return $this->response->setJSON(['status' => 'success']);
            
        } catch (\Exception $e) {
            log_message('error', 'Notification Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function determineOrderStatus($transaction, $type, $fraud)
    {
        // Map Midtrans status to our database enum values
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'accept') {
                    return 'berhasil';
                } else {
                    return 'pending';
                }
            }
        } elseif ($transaction == 'settlement') {
            return 'berhasil';
        } elseif ($transaction == 'pending') {
            return 'pending';
        } elseif ($transaction == 'deny') {
            return 'gagal';
        } elseif ($transaction == 'expire') {
            return 'gagal';
        } elseif ($transaction == 'cancel') {
            return 'gagal';
        } else {
            return 'pending';
        }
    }

    // Method untuk halaman sukses
    public function success()
    {
        $data = [
            'title' => 'Pembayaran Berhasil'
        ];
        return view('checkout/success', $data);
    }

    // Method untuk halaman pending
    public function pending()
    {
        $data = [
            'title' => 'Pembayaran Menunggu'
        ];
        return view('checkout/pending', $data);
    }

    // Method untuk halaman gagal
    public function failed()
    {
        $data = [
            'title' => 'Pembayaran Gagal'
        ];
        return view('checkout/failed', $data);
    }

    // Method untuk melihat detail order
    public function order($external_id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (!$external_id) {
            return redirect()->to('/')->with('error', 'Order tidak ditemukan');
        }

        $user_id = session()->get('user_id');
        $order = $this->pesananModel->select('pesanan.*, pembayaran.external_id, pembayaran.payment_type, users.nama_lengkap as nama, users.email, users.no_telepon, users.alamat, pesanan.no_resi')
                                 ->join('pembayaran', 'pembayaran.pesanan_id = pesanan.id', 'left')
                                 ->join('users', 'users.id = pesanan.user_id', 'left')
                                 ->where('pembayaran.external_id', $external_id)
                                 ->where('pesanan.user_id', $user_id)
                                 ->first();

        if (!$order) {
            return redirect()->to('/')->with('error', 'Order tidak ditemukan');
        }

        // Ambil detail order
        $order_details = $this->detailPesananModel->select('detail_pesanan.*, produk.nama_produk')
                                               ->join('produk', 'produk.id = detail_pesanan.produk_id')
                                               ->where('detail_pesanan.pesanan_id', $order['id'])
                                               ->findAll();

        // Fetch pengiriman data
        $pengirimanModel = new \App\Models\PengirimanModel();
        $pengiriman = $pengirimanModel->where('pesanan_id', $order['id'])->first();

        // Add shipping service and description to order array for backward compatibility
        if ($pengiriman) {
            $order['shipping_service'] = $pengiriman['jasa_pengiriman'];
            $order['shipping_description'] = $pengiriman['estimasi_pengiriman'];
        }

        // Load any existing return request for this order
        $returnModel = new \App\Models\ReturnModel();
        $returnRequest = $returnModel->where('order_id', $order['id'])->first();

        $data = [
            'order' => $order,
            'order_details' => $order_details,
            'pengiriman' => $pengiriman,
            'title' => 'Detail Order #' . $external_id
        ];

        // Attach return request if exists
        $data['return_request'] = $returnRequest;

        return view('checkout/order_detail', $data);
    }

    // Method untuk riwayat pesanan
    public function history()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user_id = session()->get('user_id');
        $orders = $this->pesananModel->select('pesanan.*, pembayaran.external_id')
                                  ->join('pembayaran', 'pembayaran.pesanan_id = pesanan.id', 'left')
                                  ->where('pesanan.user_id', $user_id)
                                  ->orderBy('pesanan.tanggal_pesanan', 'DESC')
                                  ->findAll();

        $data = [
            'orders' => $orders,
            'title' => 'Riwayat Pesanan'
        ];

        return view('checkout/history', $data);
    }

    // Method untuk test API RajaOngkir
    public function testRajaOngkir()
    {
        $results = [];
        $curl = \Config\Services::curlrequest();
        
        $endpoints = [
            'https://api.rajaongkir.com/starter/city',
            'https://pro.rajaongkir.com/api/city'
        ];
        
        $keys = array_merge([$this->rajaongkirKey], $this->alternativeKeys ?? []);
        
        foreach ($endpoints as $endpoint) {
            foreach ($keys as $key) {
                try {
                    $response = $curl->request('GET', $endpoint, [
                        'headers' => ['key' => $key],
                        'timeout' => 15
                    ]);
                    
                    $result = json_decode($response->getBody(), true);
                    
                    $results[] = [
                        'endpoint' => $endpoint,
                        'api_key' => substr($key, 0, 10) . '...',
                        'status_code' => $response->getStatusCode(),
                        'success' => isset($result['rajaongkir']['results']),
                        'cities_count' => isset($result['rajaongkir']['results']) ? count($result['rajaongkir']['results']) : 0,
                        'response' => $result
                    ];
                    
                } catch (\Exception $e) {
                    $results[] = [
                        'endpoint' => $endpoint,
                        'api_key' => substr($key, 0, 10) . '...',
                        'status_code' => 'error',
                        'success' => false,
                        'error' => $e->getMessage(),
                        'response' => null
                    ];
                }
            }
        }
        
        return $this->response->setJSON([
            'status' => 'test_completed',
            'results' => $results,
            'fallback_available' => true,
            'fallback_cities_count' => count($this->getFallbackCities('jakarta'))
        ]);
    }
}