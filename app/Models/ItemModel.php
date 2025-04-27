<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items_part';
    protected $table2 = 'items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'part_number',
        'name',
        'stock',
        'minimum_stock',
        'updated_at',
        'kategori_tabung'
    ];

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'type' => 'required',
        'stock' => 'required|numeric|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama barang harus diisi',
            'min_length' => 'Nama barang minimal 3 karakter'
        ],
        'type' => [
            'required' => 'Tipe barang harus diisi'
        ],
        'stock' => [
            'required' => 'Stok barang harus diisi',
            'numeric' => 'Stok harus berupa angka',
            'greater_than_equal_to' => 'Stok tidak boleh negatif'
        ]
    ];

    protected $skipValidation = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getStockSummary()
    {
        try {
            $bahan_baku = $this->where('category', 'bahan_baku')
                              ->selectSum('stock')
                              ->get()
                              ->getRow()
                              ->stock ?? 0;

            $tabung_3kg = $this->where('category', 'tabung_produksi')
                              ->where('type', '3kg')
                              ->selectSum('stock')
                              ->get()
                              ->getRow()
                              ->stock ?? 0;

            $tabung_12kg = $this->where('category', 'tabung_produksi')
                               ->where('type', '12kg')
                               ->selectSum('stock')
                               ->get()
                               ->getRow()
                               ->stock ?? 0;

            return [
                'bahan_baku' => (int)$bahan_baku,
                'tabung_3kg' => (int)$tabung_3kg,
                'tabung_12kg' => (int)$tabung_12kg
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error in getStockSummary: ' . $e->getMessage());
            return [
                'bahan_baku' => 0,
                'tabung_3kg' => 0,
                'tabung_12kg' => 0
            ];
        }
    }

    public function updateStock($id, $quantity, $type = 'masuk')
    {
        try {
            log_message('info', "=== Start updateStock ===");
            log_message('info', "Parameters - ID: {$id}, Quantity: {$quantity}, Type: {$type}");
            
            $this->db->transStart();
            
            // Find the item and log its current state
            $item = $this->find($id);
            log_message('info', "Current item data: " . json_encode($item));
            
            if (!$item) {
                log_message('error', 'Item not found with ID: ' . $id);
                $this->db->transRollback();
                return false;
            }

            // Validate stock value
            if (!isset($item['stock']) || !is_numeric($item['stock'])) {
                log_message('error', 'Invalid current stock value: ' . ($item['stock'] ?? 'undefined'));
                $this->db->transRollback();
                return false;
            }

            // Validate quantity
            if (!is_numeric($quantity)) {
                log_message('error', 'Invalid quantity value: ' . $quantity);
                $this->db->transRollback();
                return false;
            }

            // Calculate new stock
            $currentStock = (int)$item['stock'];
            $changeAmount = (int)$quantity;
            $newStock = $type === 'masuk' ? ($currentStock + $changeAmount) : ($currentStock - $changeAmount);
            
            log_message('info', "Stock calculation - Current: {$currentStock}, Change: {$changeAmount}, New: {$newStock}");

            if ($newStock < 0) {
                log_message('error', "Stock would become negative - Current: {$currentStock}, Change: -{$changeAmount}");
                $this->db->transRollback();
                return false;
            }

            // Prepare update data
            $updateData = ['stock' => $newStock];
            log_message('info', "Updating item {$id} with data: " . json_encode($updateData));

            // Update the stock
            $result = $this->update($id, $updateData);
            
            if ($result === false) {
                log_message('error', 'Database update failed');
                log_message('error', 'Last database error: ' . json_encode($this->db->error()));
                $this->db->transRollback();
                return false;
            }

            // Verify the update
            $updatedItem = $this->find($id);
            log_message('info', "Updated item data: " . json_encode($updatedItem));

            if ((int)$updatedItem['stock'] != $newStock) {
                log_message('error', "Stock verification failed - Expected: {$newStock}, Actual: {$updatedItem['stock']}");
                $this->db->transRollback();
                return false;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                log_message('error', 'Transaction failed to complete');
                return false;
            }

            log_message('info', "=== Successfully updated stock ===");
            return true;

        } catch (\Exception $e) {
            log_message('error', "=== Error in updateStock ===");
            log_message('error', 'Message: ' . $e->getMessage());
            log_message('error', 'File: ' . $e->getFile());
            log_message('error', 'Line: ' . $e->getLine());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            
            if (isset($this->db) && $this->db->transStatus() === true) {
                $this->db->transRollback();
            }
            return false;
        }
    }

    public function getBahanBakuSummary()
    {
        try {
            $builder = $this->db->table('items_part')
                ->select('
                    id,
                    part_number,
                    name,
                    stock,
                    minimum_stock
                ')
                ->orderBy('stock', 'asc');

            // Log query untuk debugging
            $sql = $builder->getCompiledSelect(false);
            log_message('info', 'QUERY BAHAN BAKU SUMMARY: ' . $sql);
            
            $result = $builder->get()->getResultArray();
            
            // Log hasil query
            log_message('info', 'HASIL QUERY BAHAN BAKU: ' . count($result) . ' records ditemukan');
            
            // Format data untuk tampilan
            foreach ($result as &$item) {
                $item['status'] = $item['stock'] <= $item['minimum_stock'] ? 'warning' : 'normal';
                // $item['expired_status'] = strtotime($item['expired_date']) < time() ? 'expired' : 'active';
            }
            
            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Error in getBahanBakuSummary: ' . $e->getMessage());
            return [];
        }
    }

    public function getBahanBakuItems($perPage = 10, $currentPage = 1)
    {
        try {
            $offset = ($currentPage - 1) * $perPage;
            
            $builder = $this->db->table('items_part');
            $builder->select('
                id,
                part_number,
                name,
                stock,
                minimum_stock,
                updated_at
            ');
            $builder->orderBy('name', 'ASC');
            
            // Get total rows for pagination
            $total = $builder->countAllResults(false);
            
            // Get paginated data
            $items = $builder->limit($perPage, $offset)->get()->getResultArray();
            
            // Set up pagination
            $this->pager = service('pager');
            $this->pager->setPath('stok/bahan-baku');
            $this->pager->makeLinks($currentPage, $perPage, $total);
            
            return $items;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getBahanBakuItems: ' . $e->getMessage());
            return [];
        }
    }

    public function updateBahanBaku($id, $data)
    {
        try {
            log_message('info', '[ItemModel] Starting updateBahanBaku for ID: ' . $id);
            
            // Gunakan query builder langsung
            $builder = $this->db->table('items_part');
            
            // Set data untuk update dengan konversi ke integer
            $updateData = [
                'stock' => (int)$data['stock'],
                'minimum_stock' => (int)$data['minimum_stock'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Debug log query
            log_message('info', '[ItemModel] Update data: ' . json_encode($updateData));
            
            // Eksekusi update dengan metode yang benar
            $success = $builder->where('id', $id)
                              ->update($updateData);  // Langsung menggunakan update() dengan data
            
            // Log hasil update
            log_message('info', '[ItemModel] Update result: ' . ($success ? 'success' : 'failed'));
            log_message('info', '[ItemModel] Last query: ' . $this->db->getLastQuery());
            
            if ($success) {
                // Verifikasi data setelah update
                $updatedData = $builder->where('id', $id)->get()->getRowArray();
                log_message('info', '[ItemModel] Updated data: ' . json_encode($updatedData));
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', '[ItemModel] Error in updateBahanBaku: ' . $e->getMessage());
            return false;
        }
    }

    public function getStokByJenis($jenis)
    {
        $builder = $this->db->table($this->table);
        return $builder->where('name LIKE', '%' . $jenis . 'kg%')
                      ->select('SUM(stock) as total_stok')
                      ->get()
                      ->getRow()
                      ->total_stok ?? 0;
    }

    public function getLaporanTabung()
{
    $builder = $this->db->table($this->table2 . ' k');
    
    // Get current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');
    
    $builder->select('
        k.*, 
        COALESCE(b.totalKeluar, 0) AS totalKeluar,
        COALESCE(m.totalMasuk, 0) AS totalMasuk,
        CASE 
            WHEN MONTH(k.updated_at) = ' . $currentMonth . ' 
            AND YEAR(k.updated_at) = ' . $currentYear . ' 
            THEN "Ya" 
            ELSE "Tidak" 
        END as update_bulan_ini,
        CASE 
            WHEN YEAR(k.updated_at) = ' . $currentYear . ' 
            THEN "Ya" 
            ELSE "Tidak" 
        END as update_tahun_ini
    ')
    ->where('k.name LIKE', '%kg%')
    ->join('(
        SELECT 
            item_id, 
            SUM(quantity) AS totalKeluar 
        FROM 
            transactions 
        WHERE 
            type = "keluar" 
        GROUP BY 
            item_id
    ) AS b', 'k.id = b.item_id', 'left')
    ->join('(
        SELECT 
            item_id, 
            SUM(quantity) AS totalMasuk 
        FROM 
            transactions 
        WHERE 
            type = "masuk" 
        GROUP BY 
            item_id
    ) AS m', 'k.id = m.item_id', 'left')
    ->orderBy('k.name', 'ASC');
    
    $query = $builder->get();
    
    // Log query untuk debugging
    log_message('debug', 'Last Query: ' . $this->db->getLastQuery());
    
    return $query->getResultArray();
}


    // Method untuk laporan bahan baku
    public function getLaporanBahanBaku()
    {
        $builder = $this->db->table($this->table . ' i');
        
        // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $builder->select('
            i.id, 
            i.part_number, 
            i.name, 
            i.stock, 
            i.minimum_stock, 
            i.updated_at,
            CASE 
                WHEN MONTH(i.updated_at) = ' . $currentMonth . ' 
                AND YEAR(i.updated_at) = ' . $currentYear . ' 
                THEN "Ya" 
                ELSE "Tidak" 
            END as update_bulan_ini,
            CASE 
                WHEN YEAR(i.updated_at) = ' . $currentYear . ' 
                THEN "Ya" 
                ELSE "Tidak" 
            END as update_tahun_ini
        ')
        ->orderBy('i.name', 'ASC');
        
        $query = $builder->get();
        
        // Log query untuk debugging
        log_message('debug', 'Last Query: ' . $this->db->getLastQuery());
        
        return $query->getResultArray();
    }
} 