<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryOrderModel extends Model
{
    protected $table = 'delivery_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'delivery_number',
        'transaction_id',
        'delivery_date',
        'receiver_name',
        'receiver_phone',
        'delivery_address',
        'status',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'delivery_number' => 'required',
        'transaction_id' => 'required|numeric',
        'delivery_date' => 'required|valid_date',
        'receiver_name' => 'required',
        'receiver_phone' => 'required',
        'delivery_address' => 'required',
        'created_by' => 'required|numeric',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];

    public function getDeliveryOrders()
    {
        return $this->select('delivery_orders.*, transactions.id as transactions_id')
            ->join('transactions', 'transactions.id = delivery_orders.transaction_id')
            ->orderBy('delivery_orders.delivery_date', 'DESC')
            ->findAll();
    }

    public function getDeliveryOrder($id)
    {
        return $this->select('delivery_orders.*, transaction.id as transaction_id, transaction.customer_name, transaction.total_amount')
            ->join('transaction', 'transaction.id = delivery_orders.transaction_id')
            ->where('delivery_orders.id', $id)
            ->first();
    }

    public function generateDeliveryNumber()
    {
        $date = date('Ymd');
        $lastNumber = $this->select('delivery_number')
            ->like('delivery_number', 'SJ-' . $date)
            ->orderBy('delivery_number', 'DESC')
            ->first();

        if ($lastNumber) {
            $increment = (int) substr($lastNumber['delivery_number'], -4);
            $increment++;
        } else {
            $increment = 1;
        }

        return 'SJ-' . $date . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }
} 