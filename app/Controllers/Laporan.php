<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransaksiTabungModel;

class Laporan extends BaseController
{
    protected $itemModel;
    protected $transaksiTabungModel;
    
    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->transaksiTabungModel = new TransaksiTabungModel();
    }
    
    public function bahanBaku()
    {
        $data['items'] = $this->itemModel->getLaporanBahanBaku();
        return view('laporan/bahan_baku', $data);
    }
    
    public function tabung()
    {
        $data = [
            'stok_3kg' => $this->itemModel->getStokByJenis('3'),
            'stok_5kg' => $this->itemModel->getStokByJenis('5'),
            'stok_12kg' => $this->itemModel->getStokByJenis('12'),
            'stok_15kg' => $this->itemModel->getStokByJenis('15'),
            'tabung' => $this->itemModel->getLaporanTabung()
        ];
        
        return view('laporan/tabung', $data);
    }
    
    public function transaksiTabung()
    {
        $tanggalAwal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        
        $data = [
            'transaksi' => $this->transaksiTabungModel->getLaporanTransaksi($tanggalAwal, $tanggalAkhir),
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir
        ];
        
        return view('laporan/transaksi_tabung', $data);
    }
    
    public function stokOpname()
    {
        $stockOpnameModel = new \App\Models\StockOpnameModel();
        $type = $this->request->getGet('type') ?? 'tabung';
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01'); // Default awal bulan ini
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d'); // Default hari ini

        $data = [
            'title' => 'Laporan Stok Opname ' . ucfirst($type),
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'opname_history' => $stockOpnameModel->getLaporanOpname($type, $start_date, $end_date)
        ];

        return view('laporan/stok_opname', $data);
    }
    
    // Export methods jika diperlukan
    public function exportBahanBaku()
    {
        // Implementation for exporting bahan baku report
    }
} 