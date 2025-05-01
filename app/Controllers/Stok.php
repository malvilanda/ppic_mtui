<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\StockOpnameModel;
use App\Models\WarehouseModel;
use App\Models\OpnameBahanModel;
use App\Models\BahanBakuModel;
use App\Models\TransactionBahanBakuModel;

class Stok extends BaseController
{
    protected $itemModel;
    protected $stockOpnameModel;
    protected $warehouseModel;
    protected $opnameBahanModel;
    protected $bahanBakuModel;
    protected $transactionBahanBakuModel;
    protected $table_tabung = 'items';

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->stockOpnameModel = new StockOpnameModel();
        $this->warehouseModel = new WarehouseModel();
        $this->opnameBahanModel = new OpnameBahanModel();
        $this->bahanBakuModel = new BahanBakuModel();
        $this->transactionBahanBakuModel = new TransactionBahanBakuModel();
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
        // Get filter parameters
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');
        
        // Format date for filtering
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        // Ambil data tabung dari tabel items menggunakan $table_tabung
        $data['items'] = $this->itemModel->builder($this->table_tabung)
            ->select("$this->table_tabung.*, 
                     COALESCE(SUM(CASE WHEN transactions.transaction_date >= '$startDate' AND transactions.transaction_date <= '$endDate' THEN transactions.quantity ELSE 0 END), 0) as total_keluar")
            ->join('transactions', "transactions.item_id = $this->table_tabung.id AND transactions.type = 'keluar'", 'left')
            ->where("$this->table_tabung.name LIKE", '%3kg%')
            ->orWhere("$this->table_tabung.name LIKE", '%12kg%')
            ->groupBy("$this->table_tabung.id")
            ->get()
            ->getResultArray();
            
        // Add filter data to view
        $data['current_month'] = $month;
        $data['current_year'] = $year;

        return view('stok/tabung', $data);
    }

    public function bahanBaku()
    {
        // Get current page from url
        $currentPage = $this->request->getGet('page') ?? 1;
        
        // Get items with pagination
        $data = $this->itemModel->getBahanBakuItems(8, $currentPage);
        
        // Add current page to data
        $data['current_page'] = $currentPage;
        
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
            'opname_bahan' => $this->opnameBahanModel->getOpnameBahan(),
            'bahan_baku' => $this->bahanBakuModel->findAll()
        ];

        return view('stok/opname_bahan_baku', $data);
    }

    public function get_stok_bahan($id)
    {
        $bahan = $this->bahanBakuModel->find($id);
        
        if ($bahan) {
            return $this->response->setJSON([
                'status' => 'success',
                'stok' => $bahan['stok']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Data bahan tidak ditemukan'
        ]);
    }

    public function simpan_opname_bahan()
    {
        // Hitung selisih
        $stok_sistem = $this->request->getVar('stok_sistem');
        $stok_fisik = $this->request->getVar('stok_fisik');
        $selisih = $this->opnameBahanModel->hitungSelisih($stok_sistem, $stok_fisik);

        // Data untuk disimpan
        $data = [
            'tanggal' => $this->request->getVar('tanggal'),
            'bahan_id' => $this->request->getVar('bahan_id'),
            'stok_sistem' => $stok_sistem,
            'stok_fisik' => $stok_fisik,
            'selisih' => $selisih,
            'keterangan' => $this->request->getVar('keterangan')
        ];

        // Simpan data opname
        $this->opnameBahanModel->save($data);

        // Update stok bahan baku
        $bahan = $this->bahanBakuModel->find($data['bahan_id']);
        $this->bahanBakuModel->update($data['bahan_id'], [
            'stok' => $stok_fisik
        ]);

        session()->setFlashdata('pesan', 'Data opname berhasil ditambahkan.');
        return redirect()->to('/stok/opname_bahan_baku');
    }

    public function hapus_opname_bahan($id)
    {
        $this->opnameBahanModel->delete($id);
        session()->setFlashdata('pesan', 'Data opname berhasil dihapus.');
        return redirect()->to('/stok/opname_bahan_baku');
    }
} 