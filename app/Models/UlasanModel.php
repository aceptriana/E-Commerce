<?php

namespace App\Models;

use CodeIgniter\Model;

class UlasanModel extends Model
{
    // Nama tabel di database
    protected $table = 'ulasan';

    // Primary key tabel
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi (mass assignment)
    protected $allowedFields = [
        'user_id',    // ID pengguna yang memberikan ulasan
        'produk_id',  // ID produk yang diulas
        'rating',     // Nilai rating yang diberikan
        'komentar',   // Komentar atau feedback dari pengguna
        'tanggal',    // Tanggal ulasan diberikan
    ];

    // Menentukan tipe data untuk kolom tertentu
    protected $dateFormat = 'datetime';

    // Menentukan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    // Menentukan relasi dengan model Produk
    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'produk_id');
    }

    // Mendapatkan ulasan berdasarkan rating tertentu
    public function getUlasanByRating(int $rating)
    {
        return $this->where('rating', $rating)->findAll();
    }

    // Mendapatkan ulasan terbaru untuk produk tertentu
    public function getUlasanTerbaru(int $produk_id)
    {
        return $this->where('produk_id', $produk_id)
                    ->orderBy('tanggal', 'desc')
                    ->limit(5)  // Mendapatkan 5 ulasan terbaru
                    ->findAll();
    }

    // Mendapatkan rata-rata rating untuk produk tertentu
    public function getRataRataRating(int $produk_id)
    {
        return $this->where('produk_id', $produk_id)
                    ->selectAvg('rating')  // Mengambil rata-rata rating dari produk
                    ->first();
    }
}
