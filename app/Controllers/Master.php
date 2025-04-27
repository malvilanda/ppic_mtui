<?php

namespace App\Controllers;

use App\Models\ItemTypeModel;
use App\Models\ItemCategoryModel;

class Master extends BaseController
{
    protected $itemTypeModel;
    protected $itemCategoryModel;

    public function __construct()
    {
        $this->itemTypeModel = new ItemTypeModel();
        $this->itemCategoryModel = new ItemCategoryModel();
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
        $db = \Config\Database::connect();
        $builder = $db->table('items_part');
        
        // Get search keyword
        $keyword = $this->request->getGet('keyword');
        
        // Apply search filter
        if (!empty($keyword)) {
            $builder->like('name', $keyword)
                    ->orLike('part_number', $keyword);
        }
        
        // Count total results for pagination
        $total = $builder->countAllResults(false);
        
        // Setup pagination
        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;
        
        // Get data with pagination
        $items_part = $builder->orderBy('name', 'ASC')
                      ->limit($perPage, $offset)
                      ->get()
                      ->getResultArray();
        
        // Setup pager
        $pager = service('pager');
        $pager->setPath('master/bahan-baku');
        $pager->makeLinks($currentPage, $perPage, $total);
        
        $data = [
            'title' => 'Master Bahan Baku',
            'categories' => $this->itemCategoryModel->findAll(),
            'types' => $this->itemTypeModel->where('category', 'bahan_baku')->findAll(),
            'items_part' => $items_part,
            'pager' => $pager,
            'keyword' => $keyword,
            'total' => $total
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
        // Log untuk debugging
        log_message('debug', '===============================================');
        log_message('debug', 'storeItemsPart method called');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Form data: name=' . $this->request->getPost('name') . ', part_number=' . $this->request->getPost('part_number'));
        
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'part_number' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $name = $this->request->getPost('name');
        $partNumber = $this->request->getPost('part_number');
        
        // Log nilai variabel
        log_message('debug', 'Variables: name=' . $name . ', partNumber=' . $partNumber);
        
        // Siapkan data untuk disimpan
        $data = [
            'name' => $name,
            'part_number' => $partNumber,
            'stock' => 0, // Default stok 0
            'minimum_stock' => 0, // Default minimum stok 0
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('debug', 'Data to insert: ' . json_encode($data));
        
        // Simpan ke database
        try {
            $db = \Config\Database::connect();
            log_message('debug', 'Database connection established');
            
            $builder = $db->table('items_part');
            log_message('debug', 'Table builder created');
            
            $result = $builder->insert($data);
            log_message('debug', 'Insert result: ' . ($result ? 'true' : 'false'));
            log_message('debug', 'Last query: ' . $db->getLastQuery());
            
            // Redirect kembali dengan pesan sukses
            return redirect()->to('master/bahan-baku')->with('success', 'Bahan baku berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error in storeItemsPart: ' . $e->getMessage());
            log_message('error', 'Error trace: ' . $e->getTraceAsString());
            return redirect()->to('master/bahan-baku')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 