<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'user_id', 'reason', 'status', 'admin_note', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
