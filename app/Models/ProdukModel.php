<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_produk', 
        'deskripsi', 
        'harga', 
        'stok', 
        'berat',
        'kategori_id', 
        'is_preorder', 
        'tanggal_rilis'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'dibuat_pada';
    protected $updatedField = 'diperbarui_pada';
    
    // Ambil semua produk dengan nama kategori
    public function getProdukWithKategori()
    {
        return $this->select('produk.*, kategori.nama_kategori')
                   ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
                   ->findAll();
    }
    
    // Ambil produk berdasarkan ID dengan nama kategori
    public function getProdukById($id)
    {
        return $this->select('produk.*, kategori.nama_kategori')
                   ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
                   ->where('produk.id', $id)
                   ->first();
    }
    
    // Ambil foto-foto produk
    public function getFotoProduk($produk_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('foto_produk');
        $builder->where('produk_id', $produk_id);
        $builder->orderBy('urutan', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Method untuk mengurangi stok produk
    public function reduceStock($produk_id, $quantity)
    {
        $produk = $this->find($produk_id);
        if ($produk) {
            $newStock = max(0, $produk['stok'] - $quantity);
            return $this->update($produk_id, ['stok' => $newStock]);
        }
        return false;
    }

    // Method untuk mengecek ketersediaan stok
    public function checkStock($produk_id, $quantity)
    {
        $produk = $this->find($produk_id);
        return $produk && $produk['stok'] >= $quantity;
    }
    
    // Method untuk mengambil produk dengan kategori, search, dan pagination
    public function getProdukWithKategoriPaginated($search = null, $page = 1, $perPage = 10)
    {
        $builder = $this->select('produk.*, kategori.nama_kategori')
                       ->join('kategori', 'kategori.id = produk.kategori_id', 'left');
        
        // Apply search filter if provided
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('produk.nama_produk', $search)
                   ->orLike('kategori.nama_kategori', $search)
                   ->orLike('produk.deskripsi', $search)
                   ->groupEnd();
        }
        
        // Get total count for pagination
        $totalRecords = $builder->countAllResults(false);
        
        // Calculate pagination
        $totalPages = ceil($totalRecords / $perPage);
        $offset = ($page - 1) * $perPage;
        
        // Get paginated data
        $data = $builder->limit($perPage, $offset)
                       ->orderBy('produk.dibuat_pada', 'DESC')
                       ->get()
                       ->getResultArray();
        
        // Create pager object
        $pager = service('pager');
        $pager->store('admin_produk', $page, $perPage, $totalRecords);
        
        return [
            'data' => $data,
            'pager' => $pager,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'currentPage' => $page,
            'perPage' => $perPage
        ];
    }
}