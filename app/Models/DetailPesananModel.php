<?php

// File: app/Models/DetailPesananModel.php
namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'pesanan_id', 'produk_id', 'jumlah', 'harga_satuan', 
        'ukuran', 'warna', 'bahan', 'finishing'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}