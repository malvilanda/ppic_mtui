<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemsPartModel extends Model
{
    protected $table = 'items_part';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'warehouse_id', 'part_number'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
} 