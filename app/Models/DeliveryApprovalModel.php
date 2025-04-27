<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryApprovalModel extends Model
{
    protected $table = 'delivery_approvals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'transaction_id',
        'delivery_order',
        'requested_by',
        'approved_by',
        'status',
        'notes',
        'requested_at',
        'approved_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'transaction_id' => 'required|numeric',
        'delivery_order' => 'required',
        'requested_by' => 'required|numeric',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];

    public function getPendingApprovals()
    {
        return $this->select('
                delivery_approvals.*,
                transactions.quantity,
                items.name as item_name,
                clients.name as client_name,
                requester.name as requester_name,
                approver.name as approver_name
            ')
            ->join('transactions', 'transactions.id = delivery_approvals.transaction_id')
            ->join('items', 'items.id = transactions.item_id')
            ->join('clients', 'clients.client_id = transactions.client_id')
            ->join('users as requester', 'requester.id = delivery_approvals.requested_by')
            ->join('users as approver', 'approver.id = delivery_approvals.approved_by', 'left')
            ->where('delivery_approvals.status', 'pending')
            ->orderBy('delivery_approvals.requested_at', 'DESC')
            ->findAll();
    }

    public function getApprovalHistory()
    {
        return $this->select('
                delivery_approvals.*,
                transactions.quantity,
                items.name as item_name,
                clients.name as client_name,
                requester.name as requester_name,
                approver.name as approver_name
            ')
            ->join('transactions', 'transactions.id = delivery_approvals.transaction_id')
            ->join('items', 'items.id = transactions.item_id')
            ->join('clients', 'clients.client_id = transactions.client_id')
            ->join('users as requester', 'requester.id = delivery_approvals.requested_by')
            ->join('users as approver', 'approver.id = delivery_approvals.approved_by', 'left')
            ->where('delivery_approvals.status !=', 'pending')
            ->orderBy('delivery_approvals.approved_at', 'DESC')
            ->findAll();
    }

    public function requestApproval($data)
    {
        $data['requested_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'pending';
        return $this->insert($data);
    }

    public function approve($id, $approver_id, $notes = null)
    {
        return $this->update($id, [
            'approved_by' => $approver_id,
            'approved_at' => date('Y-m-d H:i:s'),
            'status' => 'approved',
            'notes' => $notes
        ]);
    }

    public function reject($id, $approver_id, $notes = null)
    {
        return $this->update($id, [
            'approved_by' => $approver_id,
            'approved_at' => date('Y-m-d H:i:s'),
            'status' => 'rejected',
            'notes' => $notes
        ]);
    }
} 