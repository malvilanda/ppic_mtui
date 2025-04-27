<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryApprovalModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'delivery_order_id',
        'status',
        'notes',
        'approved_by',
        'approved_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'delivery_order_id' => 'required|numeric',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];

    public function getPendingApprovals()
    {
        return $this->db->table('transactions as t')
            ->select('t.id, t.delivery_order, 
            t.transaction_date, i.name as item_name, 
            t.quantity, c.name as client_name, u.name as requester_name')
            ->join('items as i', 'i.id = t.item_id')
            ->join('clients as c', 'c.client_id = t.client_id')
            ->join('users as u', 'u.id = t.user_id')
            ->where('t.status', null)
            ->orWhere('t.status', 'pending')
            ->get()
            ->getResultArray();
    }

    public function getApprovalHistory()
    {
        return $this->db->table('transactions as t')
            ->select('t.id, t.delivery_order, 
            t.transaction_date, i.name as item_name, 
            c.name as client_name, t.status, u.name as approver_name, t.notes')
            ->join('items as i', 'i.id = t.item_id')
            ->join('clients as c', 'c.client_id = t.client_id')
            ->join('users as u', 'u.id = t.user_id')
            ->where('t.status !=', null)
            ->where('t.status !=', 'pending')
            ->orderBy('t.transaction_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function requestApproval($data)
    {
        $data['transaction_date'] = date('Y-m-d H:i:s');
        $data['status'] = 'pending';
        return $this->insert($data);
    }

    public function approve($id, $approver_id, $notes = null)
    {
        try {
            $this->db->transStart();

            // Cek apakah transaksi ada dan statusnya valid
            $transaction = $this->db->table('transactions')
                                  ->where('id', $id)
                                  ->get()
                                  ->getRowArray();

            if (!$transaction) {
                log_message('error', "Approval failed: Transaction with ID {$id} not found");
                return false;
            }

            if ($transaction['status'] === 'completed') {
                log_message('error', "Approval failed: Transaction {$id} already completed");
                return false;
            }

            if ($transaction['status'] === 'cancelled') {
                log_message('error', "Approval failed: Transaction {$id} already cancelled");
                return false;
            }

            // Pastikan field yang akan diupdate sesuai dengan allowedFields
            $updateData = [
                'approved_by' => $approver_id,
                'approved_at' => date('Y-m-d H:i:s'),
                'status' => 'completed'
            ];

            // Tambahkan notes jika ada
            if ($notes !== null) {
                $updateData['notes'] = $notes;
            }

            // Log data yang akan diupdate untuk debugging
            log_message('info', 'Attempting to update transaction with data: ' . json_encode($updateData));

            // Update status transaksi menggunakan query builder
            $result = $this->db->table('transactions')
                              ->where('id', $id)
                              ->update($updateData);

            if ($result === false) {
                log_message('error', 'Approval failed: Database error - ' . json_encode($this->db->error()));
                $this->db->transRollback();
                return false;
            }

            // Update stok_bergerak di tabel items
            $updateStock = $this->db->table('items')
                                   ->where('id', $transaction['item_id'])
                                   ->set('stock', 'stock - ' . $transaction['quantity'], false)
                                   ->update();

            if (!$updateStock) {
                log_message('error', 'Approval failed: Could not update item stock - ' . json_encode($this->db->error()));
                $this->db->transRollback();
                return false;
            }

            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                log_message('error', 'Approval failed: Transaction rollback - ' . json_encode($this->db->error()));
                return false;
            }

            log_message('info', "Transaction {$id} successfully approved");
            return true;

        } catch (\Exception $e) {
            log_message('error', "Error in approve: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            if ($this->db->transStatus() === true) {
                $this->db->transRollback();
            }
            return false;
        }
    }

    public function reject($id, $approver_id, $notes = null)
    {
        try {
            $this->db->transStart();

            // Ambil detail transaksi
            $transaction = $this->db->table('transactions')
                                  ->where('id', $id)
                                  ->get()
                                  ->getRowArray();

            if (!$transaction) {
                log_message('error', "Reject failed: Transaction with ID {$id} not found");
                return false;
            }

            if ($transaction['status'] === 'completed' || $transaction['status'] === 'cancelled') {
                log_message('error', "Reject failed: Transaction {$id} already processed");
                return false;
            }

            // Log data transaksi untuk debugging
            log_message('info', 'Transaction data before reject: ' . json_encode($transaction));

            // Update status transaksi
            $updateData = [
                'approved_by' => $approver_id,
                'approved_at' => date('Y-m-d H:i:s'),
                'status' => 'cancelled'
            ];

            if ($notes !== null) {
                $updateData['notes'] = $notes;
            }

            // Update transaksi
            $result = $this->db->table('transactions')
                              ->where('id', $id)
                              ->update($updateData);

            if (!$result) {
                log_message('error', "Reject failed: Could not update transaction status - " . json_encode($this->db->error()));
                $this->db->transRollback();
                return false;
            }

            // Ambil data item sebelum update
            $itemBefore = $this->db->table('items')
                                  ->where('id', $transaction['item_id'])
                                  ->get()
                                  ->getRowArray();

            log_message('info', 'Item data before update: ' . json_encode($itemBefore));

            // Update stock_bergerak di tabel items
            $updateStock = $this->db->table('items')
                                   ->where('id', $transaction['item_id'])
                                   ->set('stock_bergerak', 'stock_bergerak + ' . $transaction['quantity'], false)
                                   ->update();

            if (!$updateStock) {
                log_message('error', "Reject failed: Could not update item stock - " . json_encode($this->db->error()));
                $this->db->transRollback();
                return false;
            }

            // Ambil data item setelah update untuk verifikasi
            $itemAfter = $this->db->table('items')
                                 ->where('id', $transaction['item_id'])
                                 ->get()
                                 ->getRowArray();

            log_message('info', 'Item data after update: ' . json_encode($itemAfter));

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                log_message('error', 'Reject failed: Transaction rollback - ' . json_encode($this->db->error()));
                return false;
            }

            log_message('info', "Transaction {$id} successfully rejected. Stock updated from {$itemBefore['stock_bergerak']} to {$itemAfter['stock_bergerak']}");
            return true;

        } catch (\Exception $e) {
            log_message('error', "Error in reject: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            if ($this->db->transStatus() === true) {
                $this->db->transRollback();
            }
            return false;
        }
    }

    public function getDeliveryOrderDetail($id)
    {
        try {
            $result = $this->db->table('transactions as t')
                ->select('
                    t.id,
                    t.delivery_order,
                    t.transaction_date,
                    t.status,
                    t.quantity,
                    t.notes,
                    t.approved_at,
                    t.approved_by,
                    i.name as item_name,
                    c.name as client_name,
                    requester.name as requester_name,
                    COALESCE(approver.name, "-") as approver_name
                ')
                ->join('items as i', 'i.id = t.item_id')
                ->join('clients as c', 'c.client_id = t.client_id')
                ->join('users as requester', 'requester.id = t.user_id')
                ->join('users as approver', 'approver.id = t.approved_by', 'left')
                ->where('t.id', $id)
                ->get()
                ->getRowArray();

            if (!$result) {
                log_message('error', "Delivery order with ID {$id} not found");
                return null;
            }

            // Set default values for nullable fields
            $result['status'] = $result['status'] ?? 'pending';
            $result['notes'] = $result['notes'] ?? null;
            $result['approved_at'] = $result['approved_at'] ?? null;
            $result['approver_name'] = $result['approver_name'] ?? '-';

            return $result;

        } catch (\Exception $e) {
            log_message('error', "Error in getDeliveryOrderDetail: " . $e->getMessage());
            return null;
        }
    }
} 