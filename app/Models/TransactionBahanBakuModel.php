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
                items_part.name as item_name,
                items_part.stock as remaining_stock,
                warehouses.name as warehouse_name,
                users.name as pic_name,
                units.name as unit_name
            ')
            ->join('items_part', 'items_part.id = transactions_bahan_baku.item_id')
            ->join('warehouses', 'warehouses.id = transactions_bahan_baku.warehouse_id')
            ->join('users', 'users.id = transactions_bahan_baku.user_id')
            ->join('units', 'units.id = transactions_bahan_baku.unit_id', 'left')
            ->orderBy('transactions_bahan_baku.created_at', 'DESC')
            ->findAll(20); // Batasi 20 transaksi terakhir
    }

    public function insertTransaction($data)
    {
        $this->db->transStart();
        try {
            // Set tanggal transaksi
            $data['transaction_date'] = date('Y-m-d H:i:s');
            
            // Insert transaksi
            $this->insert($data);
            
            // Update stok di tabel items_part
            $itemModel = new \App\Models\ItemModel();
            $item = $itemModel->where('id', $data['item_id'])
                            ->where('warehouse_id', $data['warehouse_id'])
                            ->first();
            
            if (!$item) {
                throw new \Exception('Bahan baku tidak ditemukan di gudang tersebut');
            }
            
            // Hitung stok baru
            $newStock = $item['stock'];
            if ($data['type'] === 'masuk') {
                $newStock += $data['quantity'];
            } else {
                // Cek stok mencukupi
                if ($item['stock'] < $data['quantity']) {
                    throw new \Exception('Stok di gudang ini tidak mencukupi untuk transaksi keluar');
                }
                $newStock -= $data['quantity'];
            }
            
            // Update stok
            $itemModel->update($item['id'], ['stock' => $newStock]);
            
            $this->db->transComplete();
            return $this->db->transStatus();
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function getCurrentStock($itemId, $warehouseId)
    {
        // Ambil stok awal dari items_part
        $itemModel = new \App\Models\ItemModel();
        $item = $itemModel->find($itemId);
        
        if (!$item) {
            throw new \Exception('Bahan baku tidak ditemukan');
        }
        
        $initialStock = $item['stock'];
        
        // Hitung total keluar untuk warehouse tertentu
        $totalKeluar = $this->where('item_id', $itemId)
            ->where('warehouse_id', $warehouseId)
            ->where('type', 'keluar')
            ->selectSum('quantity')
            ->first()['quantity'] ?? 0;
            
        return $initialStock - $totalKeluar;
    }
} 