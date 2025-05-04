<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'jenis_transaksi', // masuk/keluar
        'tipe_item',       // bahan_baku/tabung
        'id_item',
        'jumlah',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk mendapatkan laporan transaksi bahan baku
    public function getLaporanBahanBaku($start_date = null, $end_date = null, $jenis = null)
    {
        $builder = $this->db->table('transactions_bahan_baku');
        $builder->select('transactions_bahan_baku.*, items_part.name as nama_bahan, units.name as unit');
        $builder->join('items_part', 'items_part.id = transactions_bahan_baku.item_id');
        $builder->join('units', 'units.id = transactions_bahan_baku.unit_id', 'left');
        
        
        if ($start_date && $end_date) {
            $builder->where('transactions_bahan_baku.transaction_date >=', $start_date . ' 00:00:00');
            $builder->where('transactions_bahan_baku.transaction_date <=', $end_date . ' 23:59:59');
        }
        
        if ($jenis) {
            $builder->where('transactions_bahan_baku.type', $jenis);
        }
        
        $builder->orderBy('transactions_bahan_baku.transaction_date', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Fungsi untuk mendapatkan laporan transaksi tabung
    public function getLaporanTabung($start_date = null, $end_date = null, $jenis = null)
    {
        $builder = $this->db->table('transactions t');
        $builder->select('t.*, i.name as nama_tabung, c.name as nama_client, w.name as warehouse_name');
        $builder->join('items i', 'i.id = t.item_id', 'left');
        $builder->join('clients c', 'c.client_id = t.client_id', 'left');
        $builder->join('warehouses w', 'w.id = t.warehouse_id', 'left');
        
        if ($start_date && $end_date) {
            $builder->where('t.transaction_date >=', $start_date . ' 00:00:00');
            $builder->where('t.transaction_date <=', $end_date . ' 23:59:59');
        }
        
        if ($jenis) {
            $builder->where('t.type', $jenis);
        }
        
        $builder->orderBy('t.transaction_date', 'DESC');

        // Debug query
        $query = $builder->getCompiledSelect();
        log_message('debug', 'Query Tabung: ' . $query);

        $result = $builder->get()->getResultArray();
        
        // Debug hasil
        if (count($result) > 0) {
            log_message('debug', 'Sample Result: ' . json_encode($result[0]));
        } else {
            log_message('debug', 'No results found');
            log_message('debug', 'Parameters: start_date=' . $start_date . ', end_date=' . $end_date . ', jenis=' . $jenis);
        }

        return $result;
    }
} 