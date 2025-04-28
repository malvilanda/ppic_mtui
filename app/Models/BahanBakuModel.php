<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table = 'items_part';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'kode_bahan',
        'nama_bahan',
        'kategori',
        'spesifikasi',
        'satuan',
        'stok_minimum',
        'stok_tersedia',
        'harga_terakhir',
        'supplier_utama',
        'lokasi_simpan',
        'status',
        'keterangan',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'kode_bahan' => 'required|is_unique[items_part.kode_bahan,id,{id}]',
        'nama_bahan' => 'required',
        'satuan' => 'required'
    ];

    protected $validationMessages = [
        'nama_bahan' => [
            'required' => 'Nama bahan baku harus diisi'
        ],
        'kode_bahan' => [
            'required' => 'Kode bahan baku harus diisi',
            'is_unique' => 'Kode bahan baku sudah digunakan'
        ],
        'satuan' => [
            'required' => 'Satuan harus diisi'
        ]
    ];
} 