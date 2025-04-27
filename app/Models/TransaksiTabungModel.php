<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiTabungModel extends Model
{
    protected $table = 'transaksi_tabung';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_tabung',
        'type',
        'jumlah',
        'tanggal',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    // Method untuk mendapatkan laporan transaksi
    public function getLaporanTransaksi($tanggalAwal = null, $tanggalAkhir = null)
    {
        $builder = $this->db->table($this->table . ' t');
        $builder->select('t.*, i.name as nama_tabung, i.part_number')
                ->join('items_part i', 'i.id = t.id_tabung');

        if ($tanggalAwal && $tanggalAkhir) {
            $builder->where('DATE(t.tanggal) >=', $tanggalAwal)
                    ->where('DATE(t.tanggal) <=', $tanggalAkhir);
        }

        $builder->orderBy('t.tanggal', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    // Method untuk mendapatkan total transaksi
    public function getTotalTransaksi($type = null)
    {
        $builder = $this->db->table($this->table);
        
        if ($type) {
            $builder->where('type', $type);
        }
        
        return $builder->countAllResults();
    }

    // Method untuk mendapatkan summary transaksi per tabung
    public function getSummaryPerTabung()
    {
        $builder = $this->db->table($this->table . ' t');
        return $builder->select('i.name, 
                               SUM(CASE WHEN t.type = "masuk" THEN t.jumlah ELSE 0 END) as total_masuk,
                               SUM(CASE WHEN t.type = "keluar" THEN t.jumlah ELSE 0 END) as total_keluar')
                      ->join('items_part i', 'i.id = t.id_tabung')
                      ->groupBy('t.id_tabung')
                      ->get()
                      ->getResultArray();
    }

    // Method untuk mendapatkan transaksi terbaru
    public function getTransaksiTerbaru($limit = 5)
    {
        $builder = $this->db->table($this->table . ' t');
        return $builder->select('t.*, i.name as nama_tabung')
                      ->join('items_part i', 'i.id = t.id_tabung')
                      ->orderBy('t.tanggal', 'DESC')
                      ->limit($limit)
                      ->get()
                      ->getResultArray();
    }
} 