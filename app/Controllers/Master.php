<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemTypeModel;
use App\Models\ItemCategoryModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;

class Master extends BaseController
{
    protected $itemTypeModel;
    protected $itemCategoryModel;
    protected $itemModel;
    protected $warehouseModel;

    public function __construct()
    {
        $this->itemTypeModel = new ItemTypeModel();
        $this->itemCategoryModel = new ItemCategoryModel();
        $this->itemModel = new ItemModel();
        $this->warehouseModel = new WarehouseModel();
    }

    public function tabung()
    {
        $data = [
            'title' => 'Master Jenis Tabung',
            'types' => $this->itemTypeModel->where('category', 'tabung_produksi')->findAll()
        ];
        return view('master/tabung', $data);
    }

    public function bahanBaku()
    {
        $keyword = $this->request->getGet('keyword');
        
        // Get items with warehouse information
        $items = $this->itemModel->select('items_part.*, warehouses.name as warehouse_name')
            ->join('warehouses', 'warehouses.id = items_part.warehouse_id', 'left');
            
        if ($keyword) {
            $items->like('items_part.name', $keyword)
                  ->orLike('items_part.part_number', $keyword);
        }
        
        $data = [
            'items_part' => $items->findAll(),
            'warehouses' => $this->warehouseModel->findAll(),
            'keyword' => $keyword
        ];

        return view('master/bahan_baku', $data);
    }

    public function storeTabung()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'code' => 'required|is_unique[item_types.code]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
            'category' => 'tabung_produksi'
        ];

        $this->itemTypeModel->insert($data);
        return redirect()->to('master/tabung')->with('success', 'Jenis tabung berhasil ditambahkan');
    }

    public function storeBahanBaku()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'code' => 'required|is_unique[item_types.code]',
            'category' => 'required',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description')
        ];

        $this->itemTypeModel->insert($data);
        return redirect()->to('master/bahan-baku')->with('success', 'Jenis bahan baku berhasil ditambahkan');
    }

    public function deleteType($id)
    {
        $type = $this->itemTypeModel->find($id);
        
        if (empty($type)) {
            return redirect()->back()->with('error', 'Jenis item tidak ditemukan');
        }

        $this->itemTypeModel->delete($id);
        return redirect()->back()->with('success', 'Jenis item berhasil dihapus');
    }

    public function deleteItemsPart($id)
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('items_part');
            
            // Check if record exists
            $item = $builder->where('id', $id)->get()->getRowArray();
            
            if (empty($item)) {
                return redirect()->back()->with('error', 'Bahan baku tidak ditemukan');
            }
            
            // Delete the record
            $builder->where('id', $id)->delete();
            
            return redirect()->to('master/bahan-baku')->with('success', 'Bahan baku berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeItemsPart()
    {
        $part_number = $this->request->getPost('part_number');
        $warehouse_id = $this->request->getPost('warehouse_id');

        // Cek duplikasi
        if ($this->itemModel->isDuplicate($part_number, $warehouse_id)) {
            session()->setFlashdata('error', 'Part number ini sudah terdaftar di gudang yang dipilih. Silakan edit pada menu stok bahan baku.');
            return redirect()->to('master/bahan-baku');
        }

        $data = [
            'part_number' => $part_number,
            'name' => $this->request->getPost('name'),
            'warehouse_id' => $warehouse_id,
            'stock' => 0,
            'minimum_stock' => 0
        ];

        if ($this->itemModel->insert($data)) {
            session()->setFlashdata('success', 'Data bahan baku berhasil ditambahkan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan data bahan baku');
        }

        return redirect()->to('master/bahan-baku');
    }

    public function updateTabung()
    {
        $id = $this->request->getPost('id');
        $type = $this->itemTypeModel->find($id);
        
        if (empty($type)) {
            return redirect()->back()->with('error', 'Jenis tabung tidak ditemukan');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'code' => 'required|is_unique[item_types.code,id,' . $id . ']',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'description' => $this->request->getPost('description')
        ];

        $this->itemTypeModel->update($id, $data);
        return redirect()->to('master/tabung')->with('success', 'Jenis tabung berhasil diperbarui');
    }
} 