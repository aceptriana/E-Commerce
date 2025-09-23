<?php

namespace App\Models;

use CodeIgniter\Model;

class FotoProdukModel extends Model
{
    protected $table = 'foto_produk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['produk_id', 'url_foto', 'urutan'];
    
    // Simpan foto produk baru
    public function savePhotos($produk_id, $photos)
    {
        foreach ($photos as $index => $photo) {
            $this->insert([
                'produk_id' => $produk_id,
                'url_foto' => $photo,
                'urutan' => $index + 1
            ]);
        }
    }
    
    // Hapus semua foto untuk produk tertentu
    public function deleteByProdukId($produk_id)
    {
        return $this->where('produk_id', $produk_id)->delete();
    }
}