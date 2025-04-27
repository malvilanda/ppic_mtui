<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Database;

class TransaksiController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function generateDONumber()
    {
        try {
            // Format: DO/TAHUN/BULAN/NOMOR_URUT
            $year = date('Y');
            $month = date('m');
            
            // Ambil nomor urut terakhir dari database untuk bulan ini
            $lastDO = $this->db->table('transactions')
                ->where('YEAR(created_at)', $year)
                ->where('MONTH(created_at)', $month)
                ->where('delivery_order IS NOT NULL')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            // Set nomor urut
            if ($lastDO) {
                // Ambil nomor urut dari nomor DO terakhir
                $lastNumber = (int) substr($lastDO['delivery_order'], -4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // Format nomor DO
            $doNumber = sprintf("DO/%s/%s/%04d", $year, $month, $newNumber);

            return $this->response->setJSON([
                'success' => true,
                'do_number' => $doNumber
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
} 