<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Items extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        return redirect()->to(base_url('stok/bahan-baku'));
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Item Baru'
        ];
        return view('items/form', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'category' => 'required',
            'type' => 'required',
            'stock' => 'required|numeric',
            'unit' => 'required',
            'minimum_stock' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data
        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'type' => $this->request->getPost('type'),
            'stock' => $this->request->getPost('stock'),
            'unit' => $this->request->getPost('unit'),
            'minimum_stock' => $this->request->getPost('minimum_stock')
        ];

        $this->itemModel->insert($data);

        // Redirect berdasarkan kategori
        $category = $this->request->getPost('category');
        $redirectUrl = 'stok/';
        
        switch ($category) {
            case 'tabung_produksi':
                $redirectUrl .= 'tabung';
                break;
            case 'tabung_bahan_baku':
                $redirectUrl .= 'tabung-bahan-baku';
                break;
            default:
                $redirectUrl .= 'bahan-baku';
        }

        return redirect()->to(base_url($redirectUrl))->with('success', 'Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Item',
            'item' => $this->itemModel->find($id)
        ];

        if (empty($data['item'])) {
            return redirect()->back()->with('error', 'Item tidak ditemukan');
        }

        return view('items/form', $data);
    }

    public function update($id)
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'category' => 'required',
            'type' => 'required',
            'stock' => 'required|numeric',
            'unit' => 'required',
            'minimum_stock' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data
        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'type' => $this->request->getPost('type'),
            'stock' => $this->request->getPost('stock'),
            'unit' => $this->request->getPost('unit'),
            'minimum_stock' => $this->request->getPost('minimum_stock')
        ];

        $this->itemModel->update($id, $data);

        // Redirect berdasarkan kategori
        $category = $this->request->getPost('category');
        $redirectUrl = 'stok/';
        
        switch ($category) {
            case 'tabung_produksi':
                $redirectUrl .= 'tabung';
                break;
            case 'tabung_bahan_baku':
                $redirectUrl .= 'tabung-bahan-baku';
                break;
            default:
                $redirectUrl .= 'bahan-baku';
        }

        return redirect()->to(base_url($redirectUrl))->with('success', 'Item berhasil diupdate');
    }

    public function delete($id)
    {
        $item = $this->itemModel->find($id);
        
        if (empty($item)) {
            return redirect()->back()->with('error', 'Item tidak ditemukan');
        }

        $this->itemModel->delete($id);

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    public function bahanBakuDetail()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->getBahanBakuSummary();
        
        return view('items/bahan_baku_detail', $data);
    }

    // Optional: Method untuk export ke Excel
    public function exportBahanBaku()
    {
        $itemModel = new ItemModel();
        $items = $itemModel->getBahanBakuSummary();
        
        // Buat spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Part Number');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Stok');
        $sheet->setCellValue('E1', 'Minimum Stok');
        $sheet->setCellValue('F1', 'Status Stok');
        $sheet->setCellValue('G1', 'Tanggal Kadaluarsa');
        $sheet->setCellValue('H1', 'Status');
        
        // Isi data
        $row = 2;
        foreach ($items as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item['part_number']);
            $sheet->setCellValue('C' . $row, $item['name']);
            $sheet->setCellValue('D' . $row, $item['stock']);
            $sheet->setCellValue('E' . $row, $item['minimum_stock']);
            $sheet->setCellValue('F' . $row, $item['status'] === 'warning' ? 'Stok Menipis' : 'Normal');
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($item['expired_date'])));
            $sheet->setCellValue('H' . $row, $item['expired_status'] === 'expired' ? 'Kadaluarsa' : 'Aktif');
            $row++;
        }
        
        // Set style header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Stok_Bahan_Baku.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
} 