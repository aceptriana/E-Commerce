<?php

// File: app/Models/PembayaranModel.php
namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'pesanan_id', 'metode_pembayaran', 'status', 'total_bayar', 
        'waktu_bayar', 'transaction_id', 'payment_type', 'va_number', 'status_code', 'status_message', 'external_id'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}