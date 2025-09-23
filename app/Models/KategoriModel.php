<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kategori', 'foto_kategori']; // Tambahkan foto_kategori
    
    // Ambil semua kategori
    public function getAllKategori()
    {
        return $this->findAll();
    }
}
