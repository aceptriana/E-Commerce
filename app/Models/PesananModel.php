<?php
// File: app/Models/PesananModel.php
namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'user_id', 'tanggal_pesanan', 'status', 'total', 
        'alamat_pengiriman', 'no_resi'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    // Method untuk memproses pesanan setelah pembayaran berhasil
    public function processOrder($pesanan_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Update status pesanan menjadi 'diproses'
            $this->update($pesanan_id, [
                'status' => 'diproses',
                'tanggal_update' => date('Y-m-d H:i:s')
            ]);

            // 2. Ambil detail pesanan
            $detailModel = new \App\Models\DetailPesananModel();
            $produkModel = new \App\Models\ProdukModel();
            $details = $detailModel->where('pesanan_id', $pesanan_id)->findAll();

            // 3. Kurangi stok untuk setiap produk
            foreach ($details as $detail) {
                // Cek stok sebelum mengurangi
                if (!$produkModel->checkStock($detail['produk_id'], $detail['jumlah'])) {
                    throw new \Exception('Stok produk tidak mencukupi');
                }
                // Kurangi stok
                $produkModel->reduceStock($detail['produk_id'], $detail['jumlah']);
            }

            $db->transComplete();
            return true;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error processing order: ' . $e->getMessage());
            return false;
        }
    }

    // Method untuk memetakan status pembayaran ke status pesanan
    private function mapPaymentToOrderStatus($payment_status)
    {
        switch ($payment_status) {
            case 'pending':
                return 'menunggu_pembayaran';
            case 'berhasil':
                return 'diproses';
            case 'gagal':
                return 'dibatalkan';
            default:
                return 'menunggu_pembayaran';
        }
    }

    // Method untuk mengupdate status pesanan dan pembayaran
    public function updateOrderStatus($pesanan_id, $payment_status, $payment_data = [])
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Map payment status to order status
            $order_status = $this->mapPaymentToOrderStatus($payment_status);

            // Update status pesanan
            $this->update($pesanan_id, [
                'status' => $order_status,
                'tanggal_update' => date('Y-m-d H:i:s')
            ]);

            // Update pembayaran jika ada data pembayaran
            if (!empty($payment_data)) {
                $pembayaranModel = new \App\Models\PembayaranModel();
                $pembayaran = $pembayaranModel->where('pesanan_id', $pesanan_id)->first();
                
                if ($pembayaran) {
                    $pembayaranModel->update($pembayaran['id'], array_merge(
                        ['status' => $payment_status],
                        $payment_data
                    ));
                }
            }

            // Jika status pembayaran berhasil, proses pesanan
            if ($payment_status === 'berhasil') {
                $this->processOrder($pesanan_id);
            }

            $db->transComplete();
            return true;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error updating order status: ' . $e->getMessage());
            return false;
        }
    }
}
