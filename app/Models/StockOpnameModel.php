<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOpnameModel extends Model
{
    protected $table = 'stock_opname';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_id', 'warehouse_id', 'system_stock', 'actual_stock', 
        'difference', 'notes', 'created_by', 'opname_date'
    ];
    protected $useTimestamps = true;

    public function getOpnameHistory($type = 'tabung')
    {
        $builder = $this->db->table($this->table);
        $builder->select('stock_opname.*, items.name as item_name, warehouses.name as warehouse_name');
        $builder->join('items', 'items.id = stock_opname.item_id');
        $builder->join('warehouses', 'warehouses.id = stock_opname.warehouse_id');
        
        if ($type === 'tabung') {
            $builder->where('items.type', 'tabung');
        } else {
            $builder->where('items.type', 'bahan_baku');
        }
        
        $builder->orderBy('opname_date', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getLaporanOpname($type, $start_date, $end_date)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            stock_opname.*,
            items.name as item_name,
            warehouses.name as warehouse_name
        ');
        $builder->join('items', 'items.id = stock_opname.item_id');
        $builder->join('warehouses', 'warehouses.id = stock_opname.warehouse_id');
        $builder->where('DATE(stock_opname.opname_date) >=', $start_date);
        $builder->where('DATE(stock_opname.opname_date) <=', $end_date);
        
        if ($type === 'tabung') {
            $builder->where('items.type', 'tabung');
        } else {
            $builder->where('items.type', 'bahan_baku');
        }
        
        $builder->orderBy('stock_opname.opname_date', 'DESC');
        
        return $builder->get()->getResultArray();
    }
} 