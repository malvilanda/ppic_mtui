<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionBahanBakuModel;
use CodeIgniter\Database\Database;

class TransaksiController extends BaseController
{
    protected $db;
    protected $transactionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->transactionModel = new TransactionBahanBakuModel();
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

    public function saveBahanBaku()
    {
        try {
            // Validasi input
            $rules = [
                'item_id' => 'required|numeric',
                'type' => 'required|in_list[masuk,keluar]',
                'warehouse_id' => 'required|numeric',
                'quantity' => 'required|numeric|greater_than[0]',
            ];

            if ($this->request->getPost('type') === 'keluar') {
                $rules['unit_id'] = 'required|numeric';
            }

            if (!$this->validate($rules)) {
                log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Cek stok untuk transaksi keluar
            if ($this->request->getPost('type') === 'keluar') {
                $currentStock = $this->db->table('items_part')
                    ->where('id', $this->request->getPost('item_id'))
                    ->get()
                    ->getRow()
                    ->stock;

                if ($currentStock < $this->request->getPost('quantity')) {
                    return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi untuk transaksi keluar');
                }
            }

            // Siapkan data transaksi
            $data = [
                'item_id' => $this->request->getPost('item_id'),
                'warehouse_id' => $this->request->getPost('warehouse_id'),
                'user_id' => session()->get('user_id'),
                'type' => $this->request->getPost('type'),
                'quantity' => $this->request->getPost('quantity'),
                'unit_id' => $this->request->getPost('type') === 'keluar' ? $this->request->getPost('unit_id') : null,
                'notes' => $this->request->getPost('notes'),
                'transaction_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Mulai transaksi database
            $this->db->transStart();

            try {
                // Insert transaksi
                $this->db->table('transactions_bahan_baku')->insert($data);
                $transactionId = $this->db->insertID();

                if (!$transactionId) {
                    throw new \Exception('Gagal menyimpan transaksi');
                }

                // Update stok
                $updateStock = $this->request->getPost('type') === 'masuk' ? 'stock + ' : 'stock - ';
                $this->db->query(
                    "UPDATE items_part SET stock = " . $updateStock . $this->request->getPost('quantity') . 
                    " WHERE id = " . $this->request->getPost('item_id')
                );

                // Commit transaksi
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Gagal melakukan transaksi database');
                }

                return redirect()->to('transaksi/bahan-baku')->with('success', 'Transaksi berhasil disimpan');

            } catch (\Exception $e) {
                $this->db->transRollback();
                log_message('error', 'Database transaction failed: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            log_message('error', 'Error in saveBahanBaku: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
} 