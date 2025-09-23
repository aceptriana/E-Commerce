<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\PembayaranModel;
use App\Models\DetailPesananModel;
use App\Models\PengirimanModel;
use App\Models\UlasanModel;

class Dashboard extends BaseController
{
    protected $pesananModel;
    protected $produkModel;
    protected $kategoriModel;
    protected $userModel;
    protected $pembayaranModel;
    protected $detailPesananModel;
    protected $pengirimanModel;
    protected $ulasanModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->userModel = new UserModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->pengirimanModel = new PengirimanModel();
        $this->ulasanModel = new UlasanModel();
    }

    public function index()
    {
        // Mendapatkan bulan dan tahun saat ini
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        
        // Total penjualan bulan ini
        $total_penjualan_bulan = $this->pesananModel
            ->select('SUM(total) as total')
            ->where('MONTH(tanggal_pesanan)', $bulan_ini)
            ->where('YEAR(tanggal_pesanan)', $tahun_ini)
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->first();
        
        // Total penjualan tahun ini
        $total_penjualan_tahun = $this->pesananModel
            ->select('SUM(total) as total')
            ->where('YEAR(tanggal_pesanan)', $tahun_ini)
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->first();
            
        // Jumlah pesanan yang sedang diproses
        $jumlah_pesanan_diproses = $this->pesananModel
            ->where('status', 'diproses')
            ->countAllResults();
            
        // Total pesanan untuk persentase
        $total_pesanan_aktif = $this->pesananModel
            ->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim'])
            ->countAllResults();
            
        $persentase_pesanan_diproses = ($total_pesanan_aktif > 0) ? 
                                      ($jumlah_pesanan_diproses / $total_pesanan_aktif) * 100 : 0;
        
        // Jumlah pesanan yang menunggu pembayaran
        $jumlah_menunggu_pembayaran = $this->pesananModel
            ->where('status', 'menunggu_pembayaran')
            ->countAllResults();
            
        // Data untuk chart penjualan bulanan
        $penjualan_bulanan = $this->getPenjualanBulanan();
        
        // Data untuk pie chart kategori produk terlaris
        $kategori_terlaris = $this->getKategoriTerlaris();
        
        // Pesanan terbaru
        $pesanan_terbaru = $this->getPesananTerbaru();
        
        // Produk dengan stok menipis
        $produk_stok_menipis = $this->getProdukStokMenipis();
        
        // Tambahan: Pesanan pre-order
        $pesanan_preorder = $this->getPesananPreorder();
        
        // Tambahan: Statistik pembayaran berdasarkan metode
        $metode_pembayaran = $this->getMetodePembayaran();
        
        // Warna untuk kategori pada pie chart
        $kategori_warna = $this->getKategoriWarna();
        
        $data = [
            'title' => 'Dashboard Admin - Toko Kalina',
            'total_penjualan_bulan' => $total_penjualan_bulan['total'] ?? 0,
            'total_penjualan_tahun' => $total_penjualan_tahun['total'] ?? 0,
            'jumlah_pesanan_diproses' => $jumlah_pesanan_diproses,
            'persentase_pesanan_diproses' => $persentase_pesanan_diproses,
            'jumlah_menunggu_pembayaran' => $jumlah_menunggu_pembayaran,
            'penjualan_bulanan' => json_encode($penjualan_bulanan),
            'kategori_terlaris' => json_encode($kategori_terlaris),
            'pesanan_terbaru' => $pesanan_terbaru,
            'produk_stok_menipis' => $produk_stok_menipis,
            'pesanan_preorder' => $pesanan_preorder,
            'metode_pembayaran' => json_encode($metode_pembayaran),
            'kategori_warna' => $kategori_warna,
            // Tambahan untuk analisis tambahan
            'total_pelanggan' => $this->userModel->where('role', 'pelanggan')->countAllResults(),
            'produk_terlaris' => $this->getProdukTerlaris(),
            'rating_rata_rata' => $this->getRatingRataRata(),
        ];
        
        return view('admin/dashboard', $data);
    }
    
    private function getPenjualanBulanan()
    {
        // Ambil data penjualan 12 bulan terakhir
        $hasil = [];
        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');
        
        for ($i = 0; $i < 12; $i++) {
            $bulan = $bulan_sekarang - $i;
            $tahun = $tahun_sekarang;
            
            if ($bulan <= 0) {
                $bulan = 12 + $bulan;
                $tahun--;
            }
            
            $total_bulan = $this->pesananModel
                ->select('SUM(total) as total')
                ->where('MONTH(tanggal_pesanan)', $bulan)
                ->where('YEAR(tanggal_pesanan)', $tahun)
                ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
                ->first();
            
            $nama_bulan = $this->getNamaBulan($bulan);
            
            $hasil[] = [
                'bulan' => $nama_bulan,
                'total' => (float)($total_bulan['total'] ?? 0)
            ];
        }
        
        // Balik array agar urutan dari kiri ke kanan adalah dari bulan lama ke terbaru
        return array_reverse($hasil);
    }
    
    private function getNamaBulan($bulan)
    {
        $nama_bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $nama_bulan[$bulan];
    }
    
    private function getKategoriTerlaris()
    {
        // Query untuk mendapatkan kategori produk terlaris
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT k.nama_kategori, COUNT(dp.id) as jumlah_terjual
            FROM detail_pesanan dp
            JOIN produk p ON dp.produk_id = p.id
            JOIN kategori k ON p.kategori_id = k.id
            JOIN pesanan ps ON dp.pesanan_id = ps.id
            WHERE ps.status IN ('diproses', 'dikirim', 'selesai')
            GROUP BY k.id, k.nama_kategori
            ORDER BY jumlah_terjual DESC
            LIMIT 5
        ");
        
        return $query->getResultArray();
    }
    
    private function getPesananTerbaru()
    {
        // Query untuk mendapatkan 10 pesanan terbaru
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT p.*, u.nama_lengkap as nama_pelanggan
            FROM pesanan p
            JOIN users u ON p.user_id = u.id
            ORDER BY p.tanggal_pesanan DESC
            LIMIT 10
        ");
        
        return $query->getResultArray();
    }
    
    private function getProdukStokMenipis()
    {
        // Query untuk mendapatkan produk dengan stok kurang dari atau sama dengan 10
        return $this->produkModel
            ->select('id, nama_produk, stok, is_preorder')
            ->where('stok <=', 10)
            ->where('is_preorder', false) // Hanya produk non-preorder yang perlu diperhatikan stoknya
            ->orderBy('stok', 'ASC')
            ->limit(10)
            ->find();
    }
    
    private function getPesananPreorder()
    {
        // Query untuk mendapatkan pesanan preorder
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT p.*, u.nama_lengkap as nama_pelanggan
            FROM pesanan p
            JOIN users u ON p.user_id = u.id
            WHERE p.is_preorder = 1
            AND p.status IN ('diproses', 'menunggu_pembayaran')
            ORDER BY p.tanggal_estimasi_pengiriman ASC
            LIMIT 5
        ");
        
        return $query->getResultArray();
    }
    
    private function getMetodePembayaran()
    {
        // Query untuk mendapatkan statistik metode pembayaran
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT payment_type, COUNT(*) as jumlah
            FROM pembayaran
            WHERE status = 'berhasil'
            GROUP BY payment_type
            ORDER BY jumlah DESC
        ");
        
        return $query->getResultArray();
    }
    
    private function getKategoriWarna()
    {
        // Ambil semua kategori dari database
        $kategori = $this->kategoriModel->findAll();
        
        // Define warna (colors) yang akan digunakan
        $colors = [
            'text-primary',
            'text-success',
            'text-info',
            'text-warning',
            'text-danger',
            'text-secondary',
            'text-dark',
            'text-muted'
        ];
        
        $kategori_warna = [];
        $i = 0;
        
        // Assign warna untuk setiap kategori
        foreach ($kategori as $kat) {
            $kategori_warna[$kat['nama_kategori']] = $colors[$i % count($colors)];
            $i++;
        }
        
        return $kategori_warna;
    }
    
    private function getProdukTerlaris()
    {
        // Query untuk mendapatkan produk terlaris
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT p.id, p.nama_produk, SUM(dp.jumlah) as jumlah_terjual
            FROM detail_pesanan dp
            JOIN produk p ON dp.produk_id = p.id
            JOIN pesanan ps ON dp.pesanan_id = ps.id
            WHERE ps.status IN ('diproses', 'dikirim', 'selesai')
            GROUP BY p.id, p.nama_produk
            ORDER BY jumlah_terjual DESC
            LIMIT 5
        ");
        
        return $query->getResultArray();
    }
    
    private function getRatingRataRata()
    {
        // Mendapatkan rating rata-rata dari semua produk
        $rating = $this->ulasanModel->selectAvg('rating')->first();
        return number_format(($rating['rating'] ?? 0), 1);
    }
    
    public function exportLaporan()
    {
        // Fungsi untuk mengexport laporan penjualan (bisa ke PDF atau Excel)
        // Implementasi export laporan disini
        
        // Contoh sederhana redirect setelah export
        return redirect()->to(base_url('admin/dashboard'))->with('success', 'Laporan berhasil diexport!');
    }
}