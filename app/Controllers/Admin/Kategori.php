<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/kategori/index', $data);
    }

    public function create()
    {
        return view('admin/kategori/create');
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'foto_kategori' => 'uploaded[foto_kategori]|is_image[foto_kategori]|mime_in[foto_kategori,image/jpg,image/jpeg,image/png]|max_size[foto_kategori,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->request->getFile('foto_kategori');
        $namaFoto = $foto->getRandomName();
        $foto->move('uploads/kategori/', $namaFoto);

        $data = [
            'nama_kategori' => $this->request->getVar('nama_kategori'),
            'foto_kategori' => 'uploads/kategori/' . $namaFoto
        ];

        $this->kategoriModel->insert($data);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id = null)
    {
        $kategori = $this->kategoriModel->find($id);
        
        if (!$kategori) {
            return redirect()->to(base_url('admin/kategori'))->with('error', 'Kategori tidak ditemukan');
        }

        $data['kategori'] = $kategori;
        return view('admin/kategori/edit', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'foto_kategori' => 'permit_empty|is_image[foto_kategori]|mime_in[foto_kategori,image/jpg,image/jpeg,image/png]|max_size[foto_kategori,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_kategori' => $this->request->getVar('nama_kategori')
        ];

        $foto = $this->request->getFile('foto_kategori');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/kategori/', $namaFoto);
            $data['foto_kategori'] = 'uploads/kategori/' . $namaFoto;
        }

        $this->kategoriModel->update($id, $data);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil diperbarui');
    }

    public function delete($id = null)
    {
        $produkModel = new \App\Models\ProdukModel();
        $produkCount = $produkModel->where('kategori_id', $id)->countAllResults();
        
        if ($produkCount > 0) {
            return redirect()->to(base_url('admin/kategori'))->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk');
        }

        $this->kategoriModel->delete($id);
        return redirect()->to(base_url('admin/kategori'))->with('success', 'Kategori berhasil dihapus');
    }
}
