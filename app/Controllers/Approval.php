<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DeliveryApprovalModel;
use App\Controllers\BaseController;
use App\Models\TransactionModel;

class Approval extends Controller
{
    protected $deliveryApprovalModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->deliveryApprovalModel = new DeliveryApprovalModel();
        $this->transactionModel = new TransactionModel();
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

    public function approve($id = null)
    {
        // Jika ada parameter $id, berarti ini untuk approval delivery
        if ($id !== null) {
            if (!$this->request->isAJAX()) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid request method'
                ]);
            }

            $notes = $this->request->getJSON()->notes;
            $approver_id = session()->get('user_id');

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
        // Jika tidak ada parameter $id, berarti ini untuk approval transaksi
        else {
            $transactionId = $this->request->getPost('transaction_id');
            
            try {
                $this->transactionModel->update($transactionId, [
                    'status' => 'approve',
                    'approved_by' => session()->get('user_id'),
                    'approved_at' => date('Y-m-d H:i:s')
                ]);

                session()->setFlashdata('success', 'Transaksi berhasil disetujui');
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal menyetujui transaksi');
            }

            return redirect()->to('approval/transaksi');
        }
    }

    public function reject($id = null)
    {
        // Jika ada parameter $id, berarti ini untuk reject delivery
        if ($id !== null) {
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

            $approver_id = session()->get('user_id');

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
        // Jika tidak ada parameter $id, berarti ini untuk reject transaksi
        else {
            $transactionId = $this->request->getPost('transaction_id');
            $rejectReason = $this->request->getPost('reject_reason');
            
            try {
                $this->transactionModel->update($transactionId, [
                    'status' => 'reject',
                    'rejected_by' => session()->get('user_id'),
                    'rejected_at' => date('Y-m-d H:i:s'),
                    'reject_reason' => $rejectReason
                ]);

                session()->setFlashdata('success', 'Transaksi berhasil ditolak');
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal menolak transaksi');
            }

            return redirect()->to('approval/transaksi');
        }
    }

    public function transaksi()
    {
        // Ambil data transaksi yang perlu persetujuan
        $transactions = $this->transactionModel->where('status', 'pending')->findAll();

        $data = [
            'title' => 'Persetujuan Transaksi',
            'transactions' => $transactions
        ];

        return view('approval/transaksi', $data);
    }
}