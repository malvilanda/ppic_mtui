<?php

namespace App\Controllers;

use App\Models\DeliveryOrderModel;
use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;

class DeliveryOrder extends ResourceController
{
    protected $deliveryOrderModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->deliveryOrderModel = new DeliveryOrderModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Surat Jalan',
            'deliveryOrders' => $this->deliveryOrderModel->getDeliveryOrders()
        ];

        return view('delivery_order/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Surat Jalan Baru',
            'transactions' => $this->transactionModel->where('status', 'completed')
                ->orderBy('transaction_date', 'DESC')
                ->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('delivery_order/create', $data);
    }

    public function store()
    {
        // Validate input
        if (!$this->validate([
            'transaction_id' => 'required',
            'delivery_date' => 'required|valid_date',
            'receiver_name' => 'required|min_length[3]',
            'receiver_phone' => 'required|min_length[10]',
            'delivery_address' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Generate delivery number
        $deliveryNumber = $this->deliveryOrderModel->generateDeliveryNumber();

        // Store data
        $data = [
            'delivery_number' => $deliveryNumber,
            'transaction_id' => $this->request->getPost('transaction_id'),
            'delivery_date' => $this->request->getPost('delivery_date'),
            'receiver_name' => $this->request->getPost('receiver_name'),
            'receiver_phone' => $this->request->getPost('receiver_phone'),
            'delivery_address' => $this->request->getPost('delivery_address'),
            'created_by' => session()->get('user_id'),
            'status' => 'pending'
        ];

        $this->deliveryOrderModel->insert($data);
        return redirect()->to('/delivery-order')->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function view($id = null)
    {
        $deliveryOrder = $this->deliveryOrderModel->getDeliveryOrder($id);
        
        if (!$deliveryOrder) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detail Surat Jalan',
            'deliveryOrder' => $deliveryOrder
        ];

        return view('delivery_order/view', $data);
    }

    public function edit($id = null)
    {
        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $deliveryOrder = $this->deliveryOrderModel->getDeliveryOrder($id);
        
        if (!$deliveryOrder) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Surat Jalan',
            'deliveryOrder' => $deliveryOrder,
            'transactions' => $this->transactionModel->where('status', 'completed')
                ->orderBy('transaction_date', 'DESC')
                ->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('delivery_order/edit', $data);
    }

    public function update($id = null)
    {
        // Validate input
        if (!$this->validate([
            'transaction_id' => 'required',
            'delivery_date' => 'required|valid_date',
            'receiver_name' => 'required|min_length[3]',
            'receiver_phone' => 'required|min_length[10]',
            'delivery_address' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data
        $data = [
            'transaction_id' => $this->request->getPost('transaction_id'),
            'delivery_date' => $this->request->getPost('delivery_date'),
            'receiver_name' => $this->request->getPost('receiver_name'),
            'receiver_phone' => $this->request->getPost('receiver_phone'),
            'delivery_address' => $this->request->getPost('delivery_address')
        ];

        $this->deliveryOrderModel->update($id, $data);
        return redirect()->to('/delivery-order')->with('success', 'Surat jalan berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $deliveryOrder = $this->deliveryOrderModel->find($id);
        
        if (!$deliveryOrder) {
            return $this->response->setJSON(['success' => false, 'message' => 'Surat jalan tidak ditemukan.']);
        }

        $this->deliveryOrderModel->delete($id);
        return $this->response->setJSON(['success' => true, 'message' => 'Surat jalan berhasil dihapus.']);
    }
}