<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\FotoProdukModel;

class Produk extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $fotoProdukModel;
    
    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->fotoProdukModel = new FotoProdukModel();
    }
    
    public function index()
    {
        // Get search parameter
        $search = $this->request->getGet('search');
        
        // Get current page (default to 1)
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10; // 10 items per page
        
        // Get products with search and pagination
        $result = $this->produkModel->getProdukWithKategoriPaginated($search, $page, $perPage);
        
        $data = [
            'title' => 'Manajemen Produk',
            'produk' => $result['data'],
            'pager' => $result['pager'],
            'currentPage' => $page,
            'totalPages' => $result['totalPages'],
            'search' => $search,
            'totalRecords' => $result['totalRecords']
        ];
        
        return view('admin/produk/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => $this->kategoriModel->getAllKategori(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/produk/create', $data);
    }
    
    public function store()
    {
        // Validasi input
        $rules = [
            'nama_produk' => 'required|min_length[3]',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required',
            'foto_produk' => 'uploaded[foto_produk.0]|max_size[foto_produk,4096]|is_image[foto_produk]',
        ];
        
        if ($this->request->getPost('is_preorder') == 1) {
            $rules['tanggal_rilis'] = 'required|valid_date';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Simpan data produk
        $is_preorder = $this->request->getPost('is_preorder') ? 1 : 0;
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'is_preorder' => $is_preorder,
            'tanggal_rilis' => $is_preorder ? $this->request->getPost('tanggal_rilis') : null,
        ];
        
        $this->produkModel->insert($data);
        $produk_id = $this->produkModel->getInsertID();
        
        // Upload foto produk
        $files = $this->request->getFileMultiple('foto_produk');
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/produk', $newName);
                $uploadedFiles[] = 'uploads/produk/' . $newName;
            }
        }
        
        // Simpan foto produk ke database
        $this->fotoProdukModel->savePhotos($produk_id, $uploadedFiles);
        
        return redirect()->to(base_url('admin/produk'))->with('success', 'Produk berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $produk = $this->produkModel->find($id);
        if (empty($produk)) {
            return redirect()->to(base_url('admin/produk'))->with('error', 'Produk tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Produk',
            'produk' => $produk,
            'kategori' => $this->kategoriModel->getAllKategori(),
            'foto_produk' => $this->produkModel->getFotoProduk($id),
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/produk/edit', $data);
    }
    
    public function update($id)
    {
        // Validasi input
        $rules = [
            'nama_produk' => 'required|min_length[3]',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required',
        ];
        
        if ($this->request->getPost('is_preorder') == 1) {
            $rules['tanggal_rilis'] = 'required|valid_date';
        }
        
        // Validasi foto hanya jika ada file yang diupload
        $files = $this->request->getFileMultiple('foto_produk');
        if (!empty($files[0]->getName())) {
            $rules['foto_produk'] = 'uploaded[foto_produk.0]|max_size[foto_produk,4096]|is_image[foto_produk]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Update data produk
        $is_preorder = $this->request->getPost('is_preorder') ? 1 : 0;
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'is_preorder' => $is_preorder,
            'tanggal_rilis' => $is_preorder ? $this->request->getPost('tanggal_rilis') : null,
        ];
        
        $this->produkModel->update($id, $data);
        
        // Upload foto produk jika ada
        if (!empty($files[0]->getName())) {
            // Hapus foto lama
            $oldPhotos = $this->produkModel->getFotoProduk($id);
            foreach ($oldPhotos as $photo) {
                if (file_exists(FCPATH . $photo['url_foto'])) {
                    unlink(FCPATH . $photo['url_foto']);
                }
            }
            $this->fotoProdukModel->deleteByProdukId($id);
            
            // Upload foto baru
            $uploadedFiles = [];
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/produk', $newName);
                    $uploadedFiles[] = 'uploads/produk/' . $newName;
                }
            }
            
            // Simpan foto produk ke database
            $this->fotoProdukModel->savePhotos($id, $uploadedFiles);
        }
        
        return redirect()->to(base_url('admin/produk'))->with('success', 'Produk berhasil diperbarui');
    }
    
    public function delete($id)
    {
        $produk = $this->produkModel->find($id);
        if (empty($produk)) {
            return redirect()->to(base_url('admin/produk'))->with('error', 'Produk tidak ditemukan');
        }
        
        // Hapus foto produk
        $photos = $this->produkModel->getFotoProduk($id);
        foreach ($photos as $photo) {
            if (file_exists(FCPATH . $photo['url_foto'])) {
                unlink(FCPATH . $photo['url_foto']);
            }
        }
        $this->fotoProdukModel->deleteByProdukId($id);
        
        // Hapus produk
        $this->produkModel->delete($id);
        
        return redirect()->to(base_url('admin/produk'))->with('success', 'Produk berhasil dihapus');
    }
    
    public function detail($id)
    {
        $produk = $this->produkModel->getProdukById($id);
        if (empty($produk)) {
            return redirect()->to(base_url('admin/produk'))->with('error', 'Produk tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail Produk',
            'produk' => $produk,
            'foto_produk' => $this->produkModel->getFotoProduk($id)
        ];
        
        return view('admin/produk/detail', $data);
    }
}