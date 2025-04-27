<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['code', 'name', 'address', 'phone', 'email', 'pic_name', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'code' => 'required|min_length[3]|is_unique[clients.code,id,{id}]',
        'name' => 'required|min_length[3]',
        'address' => 'required',
        'phone' => 'required|numeric',
        'email' => 'permit_empty|valid_email',
        'pic_name' => 'required'
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Kode client harus diisi',
            'min_length' => 'Kode client minimal 3 karakter',
            'is_unique' => 'Kode client sudah digunakan'
        ],
        'name' => [
            'required' => 'Nama client harus diisi',
            'min_length' => 'Nama client minimal 3 karakter'
        ],
        'address' => [
            'required' => 'Alamat harus diisi'
        ],
        'phone' => [
            'required' => 'Nomor telepon harus diisi',
            'numeric' => 'Nomor telepon harus berupa angka'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid'
        ],
        'pic_name' => [
            'required' => 'Nama PIC harus diisi'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Generate unique client code
     */
    public function generateCode()
    {
        $lastClient = $this->orderBy('id', 'DESC')->first();
        
        if (!$lastClient) {
            return 'CLT001';
        }

        // Extract number from last code
        $lastNumber = (int) substr($lastClient['code'], 3);
        $newNumber = $lastNumber + 1;
        
        // Format new code with leading zeros
        return 'CLT' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get client addresses
     */
    public function getClientAddresses($clientId)
    {
        $client = $this->find($clientId);
        if (!$client) {
            return [];
        }

        // Format alamat sebagai array of objects
        return [
            [
                'id' => $client['client_id'],
                'address' => $client['address'],
                'is_main' => true
            ]
        ];
    }

    /**
     * Get active clients
     */
    public function getActiveClients()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get client transaction history
     */
    public function getClientTransactions($clientId)
    {
        $db = \Config\Database::connect();
        return $db->table('transactions')
            ->select('
                transactions.*,
                items.name as item_name,
                warehouses.name as warehouse_name
            ')
            ->join('items', 'items.id = transactions.item_id')
            ->join('warehouses', 'warehouses.id = transactions.warehouse_id')
            ->where('transactions.client_id', $clientId)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->get()
            ->getResultArray();
    }
} 