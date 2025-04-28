<?php

namespace App\Models;

use CodeIgniter\Model;

class OpnameBahanModel extends Model
{
    protected $table      = 'opname_bahan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['tanggal', 'bahan_id', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan'];

    public function getOpnameBahan($id = false)
    {
        if ($id === false) {
            return $this->select('opname_bahan.*, items_part.id as kode_bahan, items_part.name as nama_bahan, items_part.stock as stok_tersedia')
                       ->join('items_part', 'items_part.id = opname_bahan.bahan_id')
                       ->orderBy('opname_bahan.tanggal', 'DESC')
                       ->findAll();
        }

        return $this->select('opname_bahan.*, items_part.id as kode_bahan, items_part.name as nama_bahan, items_part.stock as stok_tersedia')
                   ->join('items_part', 'items_part.id = opname_bahan.bahan_id')
                   ->where('opname_bahan.id', $id)
                   ->first();
    }

    public function hitungSelisih($stok_sistem, $stok_fisik)
    {
        return $stok_fisik - $stok_sistem;
    }

    public function getOpnameWithBahan($id = false)
    {
        if ($id === false) {
            return $this->select('opname_bahan.*, items_part.id as kode_bahan, items_part.name as nama_bahan, items_part.stock as stok_tersedia')
                ->join('items_part', 'items_part.id = opname_bahan.bahan_id')
                ->findAll();
        }

        return $this->select('opname_bahan.*, items_part.id as kode_bahan, items_part.name as nama_bahan, items_part.stock as stok_tersedia')
            ->join('items_part', 'items_part.id = opname_bahan.bahan_id')
            ->find($id);
    }
} 