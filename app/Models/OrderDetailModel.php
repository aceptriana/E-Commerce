<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'produk_id', 'quantity', 'price', 'subtotal'];
    protected $useTimestamps = false;
} 