<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\StockOpnameModel;
use App\Models\WarehouseModel;

class Stok extends BaseController
{
    protected $itemModel;
    protected $stockOpnameModel;
    protected $warehouseModel;
    protected $table_tabung = 'items';

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->stockOpnameModel = new StockOpnameModel();
        $this->warehouseModel = new WarehouseModel();
    }

    public function perGudang()
    {
        $data['warehouses'] = $this->warehouseModel->getWarehouseWithStats();
        
        // Get stock data for each warehouse
        $data['warehouse_stocks'] = [];
        foreach ($data['warehouses'] as $warehouse) {
            $data['warehouse_stocks'][$warehouse['id']] = $this->warehouseModel->getWarehouseStock($warehouse['id']);
        }
        
        return view('stok/per_gudang', $data);
    }

    public function tabung()
    {
        // Ambil data tabung dari tabel items menggunakan $table_tabung
        $data['items'] = $this->itemModel->builder($this->table_tabung)
            ->select("$this->table_tabung.*, 
                     COALESCE(SUM(transactions.quantity), 0) as total_keluar")
            ->join('transactions', "transactions.item_id = $this->table_tabung.id", 'left')
            ->where("$this->table_tabung.name LIKE", '%3kg%')
            ->orWhere("$this->table_tabung.name LIKE", '%12kg%')
            ->groupBy("$this->table_tabung.id")
            ->get()
            ->getResultArray();

        return view('stok/tabung', $data);
    }

    public function bahanBaku()
    {
        $currentPage = $this->request->getVar('page_items') ?? 1;
        
        // Ambil data dari tabel items_part dengan pagination
        $data = [
            'items' => $this->itemModel->getBahanBakuItems(10, $currentPage), // 10 items per page
            'pager' => $this->itemModel->pager,
            'currentPage' => $currentPage
        ];
        
        return view('stok/bahan_baku', $data);
    }

    public function tabungBahanBaku()
    {
        // Ambil data bahan baku khusus untuk pembuatan tabung
        $data['items'] = $this->itemModel->where('category', 'tabung_bahan_baku')
            ->findAll();

        return view('stok/tabung_bahan_baku', $data);
    }

    public function updateBahanBaku()
    {
        try {
            $json = $this->request->getJSON();
            
            // Debug log
            log_message('info', '[StokController] Received data: ' . json_encode($json));
            
            // Validasi input
            if (!isset($json->id) || !isset($json->stock) || !isset($json->minimum_stock)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak lengkap'
                ]);
            }
            
            // Konversi dan validasi data ke integer
            $id = (int)$json->id;
            $stock = (int)$json->stock;
            $minStock = (int)$json->minimum_stock;
            
            if ($id <= 0 || $stock < 0 || $minStock < 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak valid'
                ]);
            }
            
            $data = [
                'stock' => $stock,
                'minimum_stock' => $minStock,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Debug log
            log_message('info', '[StokController] Attempting update for ID: ' . $id);
            log_message('info', '[StokController] Update data: ' . json_encode($data));
            
            $result = $this->itemModel->updateBahanBaku($id, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate data'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[StokController] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function opnameTabung()
    {
        $data = [
            'title' => 'Stok Opname Tabung',
            'items' => $this->itemModel->db->table($this->itemModel->table2)->get()->getResultArray(),
            'warehouses' => $this->warehouseModel->findAll(),
            'opname_history' => $this->stockOpnameModel->getOpnameHistory('tabung')
        ];

        return view('stok/opname_tabung', $data);
    }

    public function opnameBahanBaku()
    {
        $data = [
            'title' => 'Stok Opname Bahan Baku',
            'items' => $this->itemModel->findAll(),
            'warehouses' => $this->warehouseModel->findAll(),
            'opname_history' => $this->stockOpnameModel->getOpnameHistory('bahan_baku')
        ];

        return view('stok/opname_bahan_baku', $data);
    }

    public function saveOpname()
    {
        // Validasi input
        $rules = [
            'item_id' => 'required',
            'warehouse_id' => 'required',
            'actual_stock' => 'required|numeric',
            'notes' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data opname
        $data = [
            'item_id' => $this->request->getPost('item_id'),
            'warehouse_id' => $this->request->getPost('warehouse_id'),
            'system_stock' => $this->request->getPost('system_stock'),
            'actual_stock' => $this->request->getPost('actual_stock'),
            'difference' => $this->request->getPost('actual_stock') - $this->request->getPost('system_stock'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => $this->request->getPost('created_by'),
            'opname_date' => date('Y-m-d H:i:s')
        ];

        $this->stockOpnameModel->insert($data);

        // Update stok di tabel items
        $this->itemModel->update($data['item_id'], ['stock' => $data['actual_stock']]);

        return redirect()->back()->with('success', 'Data opname berhasil disimpan');
    }
} 