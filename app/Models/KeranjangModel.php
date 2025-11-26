<?php

namespace App\Models;

use CodeIgniter\Model;

class KeranjangModel extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'produk_id', 'quantity', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCartItems($userId)
    {
        return $this->select('keranjang.*, produk.nama_produk, produk.harga, MIN(foto_produk.url_foto) as foto')
                    ->join('produk', 'produk.id = keranjang.produk_id')
                    ->join('foto_produk', 'foto_produk.produk_id = produk.id', 'left')
                    ->where('keranjang.user_id', $userId)
                    ->groupBy('keranjang.id, keranjang.user_id, keranjang.produk_id, keranjang.quantity, keranjang.created_at, keranjang.updated_at, produk.nama_produk, produk.harga')
                    ->findAll();
    }

    public function getCartTotal($userId)
    {
        $items = $this->select('keranjang.quantity, produk.harga')
                      ->join('produk', 'produk.id = keranjang.produk_id')
                      ->where('keranjang.user_id', $userId)
                      ->findAll();
        
        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['harga'];
        }
        
        return $total;
    }

    public function getCartWithProducts($user_id)
    {
        return $this->select('keranjang.*, produk.nama_produk as produk_nama, produk.harga, produk.berat, MIN(foto_produk.url_foto) as gambar')
            ->join('produk', 'produk.id = keranjang.produk_id')
            ->join('foto_produk', 'foto_produk.produk_id = produk.id', 'left')
            ->where('keranjang.user_id', $user_id)
            ->groupBy('keranjang.id, keranjang.user_id, keranjang.produk_id, keranjang.quantity, keranjang.created_at, produk.nama_produk, produk.harga, produk.berat')
            ->findAll();
    }

    public function getCartWithProductsByIds($user_id, $cart_ids = [])
    {
        $builder = $this->select('keranjang.*, produk.nama_produk as produk_nama, produk.harga, produk.berat, MIN(foto_produk.url_foto) as gambar')
            ->join('produk', 'produk.id = keranjang.produk_id')
            ->join('foto_produk', 'foto_produk.produk_id = produk.id', 'left')
            ->where('keranjang.user_id', $user_id)
            ->groupBy('keranjang.id, keranjang.user_id, keranjang.produk_id, keranjang.quantity, keranjang.created_at, produk.nama_produk, produk.harga, produk.berat');

        if (!empty($cart_ids)) {
            $builder->whereIn('keranjang.id', $cart_ids);
        }

        return $builder->findAll();
    }
} 