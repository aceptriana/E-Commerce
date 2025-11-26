<?php

namespace App\Controllers;

use App\Models\UlasanModel;
use App\Models\ProdukModel;

class Ulasan extends BaseController
{
    protected $ulasanModel;
    protected $produkModel;

    public function __construct()
    {
        $this->ulasanModel = new UlasanModel();
        $this->produkModel = new ProdukModel();
    }

    public function tulis($produk_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth?redirect=ulasan/tulis/' . $produk_id))
                           ->with('error', 'Silakan login terlebih dahulu untuk menulis ulasan');
        }

        $produk = $this->produkModel->find($produk_id);
        
        if (!$produk) {
            return redirect()->to(base_url('produk'))->with('error', 'Produk tidak ditemukan');
        }

        $data = [
            'title' => 'Tulis Ulasan - ' . $produk['nama_produk'],
            'produk' => $produk
        ];

        return $this->render('ulasan/form', $data);
    }

    public function simpan()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'produk_id' => 'required|numeric',
            'rating' => 'required|numeric|greater_than[0]|less_than[6]',
            'komentar' => 'required|min_length[10]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'user_id' => session()->get('user_id'),
            'produk_id' => $this->request->getPost('produk_id'),
            'rating' => $this->request->getPost('rating'),
            'komentar' => $this->request->getPost('komentar'),
            'tanggal' => date('Y-m-d H:i:s')
        ];

        if ($this->ulasanModel->insert($data)) {
            return redirect()->to(base_url('produk/detail/' . $data['produk_id']))
                           ->with('success', 'Ulasan berhasil ditambahkan. Terima kasih atas ulasan Anda!');
        } else {
            return redirect()->back()->withInput()
                           ->with('error', 'Gagal menyimpan ulasan. Silakan coba lagi.');
        }
    }

    public function hapus($id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'))->with('error', 'Silakan login terlebih dahulu');
        }

        $ulasan = $this->ulasanModel->find($id);
        
        if (!$ulasan) {
            return redirect()->back()->with('error', 'Ulasan tidak ditemukan');
        }

        // Check if the user owns this review or is admin
        if ($ulasan['user_id'] != session()->get('user_id') && session()->get('role') != 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus ulasan ini');
        }

        if ($this->ulasanModel->delete($id)) {
            return redirect()->back()->with('success', 'Ulasan berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus ulasan');
        }
    }
}
