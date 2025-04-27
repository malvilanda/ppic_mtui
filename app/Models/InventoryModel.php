<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code',
        'name',
        'category',
        'stock',
        'minimum_stock',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    public function getTotalStock()
    {
        return $this->selectSum('stock')->get()->getRow()->stock;
    }

    public function getLowStockItems()
    {
        return $this->where('stock <=', 'minimum_stock', false)->findAll();
    }

    public function getStockByCategory($category)
    {
        return $this->where('category', $category)->findAll();
    }
} 