<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\DetailPesananModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\PembayaranModel;
use App\Models\PengirimanModel;

class Pesanan extends BaseController
{
    protected $pesananModel;
    protected $detailPesananModel;
    protected $produkModel;
    protected $userModel;
    protected $pembayaranModel;
    protected $pengirimanModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->produkModel = new ProdukModel();
        $this->userModel = new UserModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->pengirimanModel = new PengirimanModel();
    }

    public function index()
    {
        // Filter status jika ada
        $status = $this->request->getGet('status') ?? '';
        
        $db = \Config\Database::connect();
        $builder = $db->table('pesanan p');
        $builder->select('p.*, u.nama_lengkap, pembayaran.status as status_pembayaran');
        $builder->join('users u', 'p.user_id = u.id');
        $builder->join('pembayaran', 'p.id = pembayaran.pesanan_id', 'left');

        // Filter berdasarkan status jika parameter status dipilih
        if (!empty($status)) {
            $builder->where('p.status', $status);
        }

        $builder->orderBy('p.tanggal_pesanan', 'DESC');
        $pesanan = $builder->get()->getResultArray();

        $data = [
            'title' => 'Manajemen Pesanan',
            'pesanan' => $pesanan,
            'selectedStatus' => $status
        ];

        return view('admin/pesanan/index', $data);
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();
        
        // Get pesanan
        $builder = $db->table('pesanan p');
        $builder->select('p.*, u.nama_lengkap, u.email, u.no_telepon, pembayaran.status as status_pembayaran, pembayaran.metode_pembayaran, pembayaran.total_bayar, pembayaran.waktu_bayar');
        $builder->join('users u', 'p.user_id = u.id');
        $builder->join('pembayaran', 'p.id = pembayaran.pesanan_id', 'left');
        $builder->where('p.id', $id);
        $pesanan = $builder->get()->getRowArray();

        if (!$pesanan) {
            return redirect()->to(base_url('admin/pesanan'))->with('error', 'Pesanan tidak ditemukan');
        }

        // Get detail pesanan
        $builder = $db->table('detail_pesanan dp');
        $builder->select('dp.*, p.nama_produk, p.harga');
        $builder->join('produk p', 'dp.produk_id = p.id');
        $builder->where('dp.pesanan_id', $id);
        $detailPesanan = $builder->get()->getResultArray();

        // Get info pengiriman
        $pengiriman = $this->pengirimanModel->where('pesanan_id', $id)->first();

        $konfirmasiBy = null;
        if (!empty($pesanan['konfirmasi_oleh'])) {
            $konfirmasiUser = $this->userModel->find($pesanan['konfirmasi_oleh']);
            $konfirmasiBy = $konfirmasiUser ? $konfirmasiUser['nama_lengkap'] : null;
        }

        $data = [
            'title' => 'Detail Pesanan',
            'pesanan' => $pesanan,
            'detailPesanan' => $detailPesanan,
            'pengiriman' => $pengiriman,
            'konfirmasi_by' => $konfirmasiBy
        ];

        return view('admin/pesanan/detail', $data);
    }

    public function updateStatusForm($id)
    {
        $pesanan = $this->pesananModel->find($id);
        
        if (!$pesanan) {
            return redirect()->to(base_url('admin/pesanan'))->with('error', 'Pesanan tidak ditemukan');
        }

        $data = [
            'title' => 'Update Status Pesanan',
            'pesanan' => $pesanan
        ];

        return view('admin/pesanan/update_status', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        
        // Validasi
        if (!$status) {
            return redirect()->back()->with('error', 'Status harus dipilih');
        }

        // Update status pesanan
        $this->pesananModel->update($id, [
            'status' => $status
        ]);

        // Jika status = dikirim, update status pengiriman
        if ($status == 'dikirim') {
            $pengiriman = $this->pengirimanModel->where('pesanan_id', $id)->first();
            
            if ($pengiriman) {
                $this->pengirimanModel->update($pengiriman['id'], [
                    'status_pengiriman' => 'dalam_perjalanan'
                ]);
            }
        }

        // Jika status = selesai, update status pengiriman
        if ($status == 'selesai') {
            $pengiriman = $this->pengirimanModel->where('pesanan_id', $id)->first();
            
            if ($pengiriman) {
                $this->pengirimanModel->update($pengiriman['id'], [
                    'status_pengiriman' => 'sampai'
                ]);
            }
        }

        return redirect()->to(base_url('admin/pesanan/detail/' . $id))->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function updateResi($id)
    {
        $no_resi = $this->request->getPost('no_resi');
        
        // Validasi
        if (!$no_resi) {
            return redirect()->back()->with('error', 'Nomor resi tidak boleh kosong');
        }

        // Update nomor resi
        $this->pesananModel->update($id, [
            'no_resi' => $no_resi
        ]);

        return redirect()->to(base_url('admin/pesanan/detail/' . $id))->with('success', 'Nomor resi berhasil diperbarui');
    }

    public function tracking($id)
    {
        $pesanan = $this->pesananModel->find($id);
        
        if (!$pesanan) {
            return redirect()->to(base_url('admin/pesanan'))->with('error', 'Pesanan tidak ditemukan');
        }

        $pengiriman = $this->pengirimanModel->where('pesanan_id', $id)->first();

        $data = [
            'title' => 'Tracking Pesanan',
            'pesanan' => $pesanan,
            'pengiriman' => $pengiriman
        ];

        return view('admin/pesanan/tracking', $data);
    }
}