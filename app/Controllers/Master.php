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
        $data = [
            'title' => 'Master Bahan Baku',
            'categories' => $this->itemCategoryModel->findAll(),
            'types' => $this->itemTypeModel->where('category', 'bahan_baku')->findAll()
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
} 