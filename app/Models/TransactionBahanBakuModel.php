<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionBahanBakuModel extends Model
{
    protected $table = 'transactions_bahan_baku';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'item_id', 
        'warehouse_id', 
        'user_id', 
        'unit_id',
        'type', 
        'quantity', 
        'transaction_date',
        'notes'   
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getTransactions()
    {
        return $this->select('
                transactions_bahan_baku.*, 
                items.name as item_name,
                warehouses.name as warehouse_name,
                users.name as pic_name
            ')
            ->join('items', 'items.id = transactions_bahan_baku.item_id')
            ->join('warehouses', 'warehouses.id = transactions_bahan_baku.warehouse_id')
            ->join('users', 'users.id = transactions_bahan_baku.user_id')
            ->orderBy('transaction_date', 'DESC')
            ->findAll();
    }

    public function insertTransaction($data)
    {
        $data['transaction_date'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
} 