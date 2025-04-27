<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DeliveryApprovalModel;

class Approval extends Controller
{
    protected $deliveryApprovalModel;

    public function __construct()
    {
        $this->deliveryApprovalModel = new DeliveryApprovalModel();
    }

    public function delivery()
    {
        $data = [
            'pending_approvals' => $this->deliveryApprovalModel->getPendingApprovals(),
            'approval_history' => $this->deliveryApprovalModel->getApprovalHistory()
        ];
        
        return view('delivery_approval/index', $data);
    }

    public function view($id)
    {
        // Ambil data approval dari database berdasarkan $id
        $approval = $this->deliveryApprovalModel->getDeliveryOrderDetail($id);

        if (!$approval) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Surat jalan tidak ditemukan');
        }

        // Cek apakah user adalah manager atau admin dari session
        $role = session()->get('role');
        $is_manager = ($role === 'manager' || $role === 'admin');

        $data = [
            'approval' => $approval,
            'is_manager' => $is_manager,
            'title' => 'Detail Surat Jalan - ' . $approval['delivery_order']
        ];

        return view('delivery_approval/view', $data);
    }

    public function approve($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        $notes = $this->request->getJSON()->notes;
        $approver_id = session()->get('user_id'); // Ambil ID user yang sedang login

        try {
            $this->deliveryApprovalModel->approve($id, $approver_id, $notes);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Surat jalan berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyetujui surat jalan'
            ]);
        }
    }

    public function reject($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        $notes = $this->request->getJSON()->notes;
        if (empty(trim($notes))) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Alasan penolakan harus diisi'
            ]);
        }

        $approver_id = session()->get('user_id'); // Ambil ID user yang sedang login

        try {
            $this->deliveryApprovalModel->reject($id, $approver_id, $notes);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Surat jalan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menolak surat jalan'
            ]);
        }
    }
}