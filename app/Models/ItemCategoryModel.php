<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemCategoryModel extends Model
{
    protected $table = 'item_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['code', 'name', 'description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'code' => 'required|min_length[3]|is_unique[item_categories.code,id,{id}]',
        'name' => 'required|min_length[3]'
    ];

    protected $validationMessages = [
        'code' => [
            'required' => 'Kode kategori harus diisi',
            'min_length' => 'Kode kategori minimal 3 karakter',
            'is_unique' => 'Kode kategori sudah digunakan'
        ],
        'name' => [
            'required' => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 3 karakter'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Check if category is in use
     */
    public function isCategoryInUse($id)
    {
        $db = \Config\Database::connect();
        $result = $db->table('items')
            ->where('category_id', $id)
            ->countAllResults();
        
        return $result > 0;
    }

    /**
     * Get all categories with type counts
     */
    public function getCategoriesWithTypeCounts()
    {
        $db = \Config\Database::connect();
        return $this->select('
                item_categories.*,
                COUNT(item_types.id) as type_count
            ')
            ->join('item_types', 'item_types.category = item_categories.code', 'left')
            ->groupBy('item_categories.id')
            ->findAll();
    }
} 