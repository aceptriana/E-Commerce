<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pesanan_id', 
        'jasa_pengiriman', 
        'biaya_pengiriman', 
        'estimasi_pengiriman', 
        'status_pengiriman'
    ];
    
    protected $useTimestamps = false;
    protected $returnType = 'array';
}