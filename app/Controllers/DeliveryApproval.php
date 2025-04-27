<?php

namespace App\Controllers;

use App\Models\DeliveryApprovalModel;
use App\Models\TransactionModel;
use CodeIgniter\Controller;

class DeliveryApproval extends Controller
{
    protected $deliveryApprovalModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->deliveryApprovalModel = new DeliveryApprovalModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        // Cek apakah user adalah manager
        if (session()->get('role') !== 'manager') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $data = [
            'title' => 'Persetujuan Surat Jalan',
            'pending_approvals' => $this->deliveryApprovalModel->getPendingApprovals(),
            'approval_history' => $this->deliveryApprovalModel->getApprovalHistory()
        ];

        return view('delivery_approval/index', $data);
    }

    public function approve($id)
    {
        // Cek apakah user adalah manager
        if (session()->get('role') !== 'manager') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Akses ditolak. Anda tidak memiliki izin.'
            ])->setStatusCode(403);
        }

        $notes = $this->request->getPost('notes');
        $approver_id = session()->get('user_id');

        $result = $this->deliveryApprovalModel->approve($id, $approver_id, $notes);

        if ($result) {
            // Update status transaksi menjadi approved
            $approval = $this->deliveryApprovalModel->find($id);
            $this->transactionModel->update($approval['transaction_id'], [
                'status' => 'approved'
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Surat jalan berhasil disetujui.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menyetujui surat jalan.'
        ])->setStatusCode(500);
    }

    public function reject($id)
    {
        // Cek apakah user adalah manager
        if (session()->get('role') !== 'manager') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Akses ditolak. Anda tidak memiliki izin.'
            ])->setStatusCode(403);
        }

        $notes = $this->request->getPost('notes');
        $approver_id = session()->get('user_id');

        $result = $this->deliveryApprovalModel->reject($id, $approver_id, $notes);

        if ($result) {
            // Update status transaksi menjadi rejected
            $approval = $this->deliveryApprovalModel->find($id);
            $this->transactionModel->update($approval['transaction_id'], [
                'status' => 'rejected'
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Surat jalan berhasil ditolak.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal menolak surat jalan.'
        ])->setStatusCode(500);
    }

    public function view($id)
    {
        $approval = $this->deliveryApprovalModel->select('
                delivery_approvals.*,
                transactions.quantity,
                transactions.delivery_order,
                transactions.delivery_address,
                transactions.receiver_name,
                transactions.receiver_phone,
                items.name as item_name,
                items.type as item_type,
                clients.name as client_name,
                clients.address as client_address,
                clients.phone as client_phone,
                requester.name as requester_name,
                approver.name as approver_name
            ')
            ->join('transactions', 'transactions.id = delivery_approvals.transaction_id')
            ->join('items', 'items.id = transactions.item_id')
            ->join('clients', 'clients.client_id = transactions.client_id')
            ->join('users as requester', 'requester.id = delivery_approvals.requested_by')
            ->join('users as approver', 'approver.id = delivery_approvals.approved_by', 'left')
            ->find($id);

        if (!$approval) {
            return redirect()->to('/delivery-approval')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Surat Jalan',
            'approval' => $approval
        ];

        return view('delivery_approval/view', $data);
    }
} 