<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemTypeModel extends Model
{
    protected $table = 'item_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['code', 'name', 'description', 'category'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'code' => 'required|min_length[3]|is_unique[item_types.code,id,{id}]',
        'name' => 'required|min_length[3]',
        'category' => 'required'
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Kode tipe harus diisi',
            'min_length' => 'Kode tipe minimal 3 karakter',
            'is_unique' => 'Kode tipe sudah digunakan'
        ],
        'name' => [
            'required' => 'Nama tipe harus diisi',
            'min_length' => 'Nama tipe minimal 3 karakter'
        ],
        'category' => [
            'required' => 'Kategori harus diisi'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get types by category
     */
    public function getTypesByCategory($category)
    {
        return $this->where('category', $category)->findAll();
    }

    /**
     * Check if type is in use
     */
    public function isTypeInUse($id)
    {
        $db = \Config\Database::connect();
        $result = $db->table('items')
            ->where('type_id', $id)
            ->countAllResults();
        
        return $result > 0;
    }
} 