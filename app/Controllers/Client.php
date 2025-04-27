<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Client extends BaseController
{
    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Client',
            'clients' => $this->clientModel->findAll()
        ];

        return view('master/client/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Client Baru',
            'code' => $this->clientModel->generateCode()
        ];

        return view('master/client/form', $data);
    }

    public function store()
    {
        // Validasi input
        if (!$this->validate($this->clientModel->validationRules, $this->clientModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data
        $data = [
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'pic_name' => $this->request->getPost('pic_name'),
            'status' => 'active'
        ];

        $this->clientModel->insert($data);
        return redirect()->to(base_url('master/client'))->with('success', 'Client berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Client',
            'client' => $this->clientModel->find($id)
        ];

        if (empty($data['client'])) {
            return redirect()->back()->with('error', 'Client tidak ditemukan');
        }

        return view('master/client/form', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate($this->clientModel->validationRules, $this->clientModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data
        $data = [
            'code' => $this->request->getPost('code'),
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'pic_name' => $this->request->getPost('pic_name')
        ];

        $this->clientModel->update($id, $data);
        return redirect()->to(base_url('master/client'))->with('success', 'Client berhasil diupdate');
    }

    public function delete($id)
    {
        // Cek apakah client memiliki transaksi
        $transactions = $this->clientModel->getClientTransactions($id);
        if (!empty($transactions)) {
            // Jika ada transaksi, ubah status menjadi inactive
            $this->clientModel->update($id, ['status' => 'inactive']);
            return redirect()->back()->with('success', 'Status client berhasil diubah menjadi tidak aktif');
        }

        // Jika tidak ada transaksi, hapus data client
        $this->clientModel->delete($id);
        return redirect()->back()->with('success', 'Client berhasil dihapus');
    }

    public function view($id)
    {
        $data = [
            'title' => 'Detail Client',
            'client' => $this->clientModel->find($id),
            'transactions' => $this->clientModel->getClientTransactions($id)
        ];

        if (empty($data['client'])) {
            return redirect()->back()->with('error', 'Client tidak ditemukan');
        }

        return view('master/client/view', $data);
    }
} 