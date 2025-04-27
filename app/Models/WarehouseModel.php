<?php

namespace App\Models;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'location'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'location' => 'required'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama gudang harus diisi',
            'min_length' => 'Nama gudang minimal 3 karakter'
        ],
        'location' => [
            'required' => 'Lokasi gudang harus diisi'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get warehouse with transaction count
     */
    public function getWarehouseWithStats()
    {
        return $this->select('
                warehouses.*,
                COUNT(DISTINCT transactions.id) as total_transactions,
                SUM(CASE WHEN transactions.type = "masuk" THEN 1 ELSE 0 END) as total_incoming,
                SUM(CASE WHEN transactions.type = "keluar" THEN 1 ELSE 0 END) as total_outgoing
            ')
            ->join('transactions', 'transactions.warehouse_id = warehouses.id', 'left')
            ->groupBy('warehouses.id')
            ->findAll();
    }

    /**
     * Get warehouse items stock
     */
    public function getWarehouseStock($warehouse_id)
    {
        $db = \Config\Database::connect();
        
        return $db->table('transactions')
            ->select('
                items.name,
                items.type,
                SUM(CASE WHEN transactions.type = "masuk" THEN transactions.quantity ELSE -transactions.quantity END) as current_stock
            ')
            ->join('items', 'items.id = transactions.item_id')
            ->where('transactions.warehouse_id', $warehouse_id)
            ->groupBy('items.id')
            ->get()
            ->getResultArray();
    }

    public function getWarehouseStats()
    {
        $db = \Config\Database::connect();
        
        return $this->select('
                warehouses.*,
                COUNT(DISTINCT transactions.id) as total_transactions,
                SUM(CASE WHEN transactions.type = "masuk" THEN transactions.quantity ELSE 0 END) as total_incoming,
                SUM(CASE WHEN transactions.type = "keluar" THEN transactions.quantity ELSE 0 END) as total_outgoing,
                SUM(CASE WHEN transactions.type = "masuk" THEN transactions.quantity ELSE -transactions.quantity END) as current_stock
            ')
            ->join('transactions', 'transactions.warehouse_id = warehouses.id', 'left')
            ->groupBy('warehouses.id')
            ->findAll();
    }

    // Method untuk mendapatkan item-item di gudang tertentu
    public function getWarehouseItems($warehouseId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('warehouse_items')
            ->select('
                warehouse_items.*,
                items.name as item_name,
                items.code as item_code,
                items.category,
                items.type
            ')
            ->join('items', 'items.id = warehouse_items.item_id')
            ->where('warehouse_id', $warehouseId)
            ->get();

        return $query->getResultArray();
    }

    // Method untuk mengupdate stok item di gudang
    public function updateStock($warehouseId, $itemId, $quantity)
    {
        $db = \Config\Database::connect();
        
        // Cek apakah item sudah ada di gudang
        $existingItem = $db->table('warehouse_items')
            ->where('warehouse_id', $warehouseId)
            ->where('item_id', $itemId)
            ->get()
            ->getRowArray();

        if ($existingItem) {
            // Update quantity jika item sudah ada
            return $db->table('warehouse_items')
                ->where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->update(['quantity' => $quantity]);
        } else {
            // Insert new record jika item belum ada
            return $db->table('warehouse_items')
                ->insert([
                    'warehouse_id' => $warehouseId,
                    'item_id' => $itemId,
                    'quantity' => $quantity
                ]);
        }
    }

    // Method untuk mengecek kapasitas gudang
    public function checkCapacity($warehouseId)
    {
        $warehouse = $this->find($warehouseId);
        if (!$warehouse) {
            return false;
        }

        $db = \Config\Database::connect();
        
        $totalQuantity = $db->table('warehouse_items')
            ->selectSum('quantity')
            ->where('warehouse_id', $warehouseId)
            ->get()
            ->getRowArray();

        return [
            'capacity' => $warehouse['capacity'],
            'used' => $totalQuantity['quantity'] ?? 0,
            'available' => $warehouse['capacity'] - ($totalQuantity['quantity'] ?? 0),
            'percentage' => round((($totalQuantity['quantity'] ?? 0) / $warehouse['capacity']) * 100, 2)
        ];
    }
} 