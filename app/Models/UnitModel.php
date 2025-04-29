<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = 'units';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'code', 'description', 'is_active'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'code' => 'required|min_length[2]|max_length[20]|is_unique[units.code,id,{id}]',
        'description' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama unit harus diisi',
            'min_length' => 'Nama unit minimal 3 karakter',
            'max_length' => 'Nama unit maksimal 100 karakter'
        ],
        'code' => [
            'required' => 'Kode unit harus diisi',
            'min_length' => 'Kode unit minimal 2 karakter',
            'max_length' => 'Kode unit maksimal 20 karakter',
            'is_unique' => 'Kode unit sudah digunakan'
        ]
    ];

    // Get active units
    public function getActiveUnits()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
} 