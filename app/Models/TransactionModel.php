<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;
use Exception;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'transaction_number',
        'type',
        'client_id',
        'client_name',
        'status',
        'created_at',
        'created_by',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'reject_reason'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'transaction_number' => 'required',
        'type' => 'required|in_list[masuk,keluar]',
        'status' => 'required|in_list[pending,approve,reject]'
    ];

    protected $validationMessages = [
        'item_id' => [
            'required' => 'Item harus dipilih',
            'numeric' => 'Format item tidak valid'
        ],
        'warehouse_id' => [
            'required' => 'Gudang harus dipilih',
            'numeric' => 'Format gudang tidak valid'
        ],
        'type' => [
            'required' => 'Tipe transaksi harus dipilih',
            'in_list' => 'Tipe transaksi harus masuk atau keluar'
        ],
        'quantity' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih besar dari 0'
        ],
        'user_id' => [
            'required' => 'User ID harus ada',
            'numeric' => 'Format User ID tidak valid'
        ],
        'created_by' => [
            'required' => 'PIC transaksi harus diisi'
        ],
        'kategori_tabung' => [
            'required' => 'Kategori tabung harus dipilih',
            'in_list' => 'Kategori tabung harus A atau B'
        ]
    ];

    public function __construct()
    {
        parent::__construct();
        
        try {
            // Test database connection
            $this->db->initialize();
            if (!$this->db->connID) {
                throw new Exception('Database connection failed');
            }
        } catch (Exception $e) {
            log_message('error', 'Database connection error: ' . $e->getMessage());
            // Re-throw the exception to be handled by the controller
            throw $e;
        }
    }

    public function getRecentTransactions($limit = 5)
    {
        return [];
    }

    public function getTotalTransactions()
    {
        try {
            $db = \Config\Database::connect();
            
            $builder = $db->table('transactions t')
                ->join('items i', 'i.id = t.item_id')
                ->where('i.category', 'tabung_produksi')
                ->where('MONTH(t.created_at)', date('m'))
                ->where('YEAR(t.created_at)', date('Y'));
            
            return $builder->countAllResults();
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getTotalTransactions: ' . $e->getMessage());
            return 0;
        }
    }

    public function addTransaction($data)
    {
        try {
            $this->db->transStart();
            
            // Log data before insert
            log_message('debug', 'Attempting to insert transaction with data: ' . json_encode($data));

            // Insert transaction
            $this->insert($data);
            $transactionId = $this->db->insertID();
            
            if (!$transactionId) {
                log_message('error', 'Failed to get insert ID after transaction insert');
                return false;
            }

            // Update stock
            $itemModel = new ItemModel();
            $success = $itemModel->updateStock($data['item_id'], $data['quantity'], $data['type']);
            
            if (!$success) {
                log_message('error', 'Failed to update stock for item_id: ' . $data['item_id']);
                $this->db->transRollback();
                return false;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                log_message('error', 'Transaction failed to complete');
                return false;
            }

            return $transactionId;
        } catch (\Exception $e) {
            log_message('error', 'Error in addTransaction: ' . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getTransactionsByItem($item_id)
    {
        return $this->where('item_id', $item_id)
            ->orderBy('created_at', 'DESC')
            ->find();
    }

    public function getTransactionsByDate($start_date, $end_date)
    {
        return $this->where('created_at >=', $start_date)
            ->where('created_at <=', $end_date)
            ->orderBy('created_at', 'DESC')
            ->find();
    }

    public function getMonthlyIncoming()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        return $this->where('type', 'in')
                    ->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->selectSum('quantity')
                    ->get()
                    ->getRow()
                    ->quantity;
    }

    public function getMonthlyOutgoing()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        return $this->where('type', 'out')
                    ->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->selectSum('quantity')
                    ->get()
                    ->getRow()
                    ->quantity;
    }

    public function getRecentActivities($limit = 5)
    {
        return $this->select('transactions.*, inventories.name as item_name')
                    ->join('inventories', 'inventories.id = transactions.inventory_id')
                    ->orderBy('transactions.created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    public function getWeeklyTransactions()
    {
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate = date('Y-m-d');
        
        return $this->select('DATE(created_at) as date, type, SUM(quantity) as total')
                    ->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->groupBy('date, type')
                    ->orderBy('date', 'ASC')
                    ->find();
    }

    /**
     * Debug helper to get information about the last executed query
     */
    public function debugLastQuery()
    {
        try {
            if (!$this->db->connID) {
                return [
                    'error' => 'No active database connection',
                    'last_query' => null,
                    'database' => $this->db->database ?? null,
                    'hostname' => $this->db->hostname ?? null
                ];
            }

            return [
                'last_query' => $this->db->getLastQuery(),
                'error' => $this->db->error(),
                'database' => $this->db->database,
                'prefix' => $this->db->getPrefix(),
                'hostname' => $this->db->hostname
            ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
                'last_query' => null,
                'database' => $this->db->database ?? null,
                'hostname' => $this->db->hostname ?? null
            ];
        }
    }

    public function getTransactionDetail($id)
    {
        return $this->select('
            transactions.*,
            items.name as item_name,
            items.type as item_type,
            clients.name as client_name,
            clients.code as client_code,
            clients.pic_name as pic_name,
            warehouses.name as warehouse_name,
            transactions.kategori_tabung
        ')
        ->join('items', 'items.id = transactions.item_id')
        ->join('clients', 'clients.client_id = transactions.client_id', 'left')
        ->join('warehouses', 'warehouses.id = transactions.warehouse_id')
        ->where('transactions.id', $id)
        ->first();
    }

    // public function getTabungTransactions($filters = [])
    // {
    //     try {
    //         $builder = $this->select('
    //                 transactions.*,
    //                 items.name as item_name,
    //                 items.type as item_type,
    //                 items.stock as current_stock,
    //                 warehouses.name as warehouse_name,
    //                 users.name as user_name,
    //                 IFNULL(clients.name, "-") as client_name,
    //                 IFNULL(clients.code, "-") as client_code
    //             ')
    //             ->join('items', 'items.id = transactions.item_id')
    //             ->join('warehouses', 'warehouses.id = transactions.warehouse_id')
    //             ->join('users', 'users.id = transactions.user_id')
    //             ->join('clients', 'clients.id = transactions.client_id', 'left')
    //             ->where('items.category', 'tabung_produksi');

    //         // Apply filters if provided
    //         if (!empty($filters)) {
    //             if (isset($filters['date_start']) && isset($filters['date_end'])) {
    //                 $builder->where('transactions.transaction_date >=', $filters['date_start'])
    //                         ->where('transactions.transaction_date <=', $filters['date_end']);
    //             }

    //             if (isset($filters['type'])) {
    //                 $builder->where('transactions.type', $filters['type']);
    //             }

    //             if (isset($filters['warehouse_id'])) {
    //                 $builder->where('transactions.warehouse_id', $filters['warehouse_id']);
    //             }

    //             if (isset($filters['item_type'])) {
    //                 $builder->where('items.type', $filters['item_type']);
    //             }
    //         }

    //         return $builder->orderBy('transactions.transaction_date', 'DESC')
    //                       ->findAll();

    //     } catch (\Exception $e) {
    //         log_message('error', 'Error in getTabungTransactions: ' . $e->getMessage());
    //         log_message('error', 'Stack trace: ' . $e->getTraceAsString());
    //         return [];
    //     }
    // }

    public function getTabungSummary()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get items first
            $itemsQuery = $db->table('items')
                ->select('id, type, stock')
                ->where('category', 'tabung_produksi')
                ->get();
            
            $items = $itemsQuery->getResultArray();
            
            // Initialize summary with default values
            $summary = [
                '3kg' => [
                    'total_masuk' => 0,
                    'total_keluar' => 0,
                    'current_stock' => 0,
                    'sisa_stok' => 0
                ],
                '12kg' => [
                    'total_masuk' => 0,
                    'total_keluar' => 0,
                    'current_stock' => 0,
                    'sisa_stok' => 0
                ],
                '15kg' => [
                    'total_masuk' => 0,
                    'total_keluar' => 0,
                    'current_stock' => 0,
                    'sisa_stok' => 0
                ],
                '5kg' => [
                    'total_masuk' => 0,
                    'total_keluar' => 0,
                    'current_stock' => 0,
                    'sisa_stok' => 0
                ]
            ];
            
            // Set current stock from items
            foreach ($items as $item) {
                $type = str_replace('tabung_', '', $item['type']);
                if (isset($summary[$type])) {
                    $summary[$type]['current_stock'] = (int)$item['stock'];
                    $summary[$type]['sisa_stok'] = (int)$item['stock'];
                }
            }
            
            // Get transactions for this month
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            $transQuery = $db->table('transactions t')
                ->select('
                    i.type as item_type,
                    t.type as trans_type,
                    SUM(t.quantity) as total_quantity
                ')
                ->join('items i', 'i.id = t.item_id')
                ->where('i.category', 'tabung_produksi')
                ->where("MONTH(t.transaction_date) = $currentMonth")
                ->where("YEAR(t.transaction_date) = $currentYear")
                ->groupBy('i.type, t.type')
                ->get();
            
            $transactions = $transQuery->getResultArray();
            
            // Calculate totals from transactions
            foreach ($transactions as $trans) {
                $type = str_replace('tabung_', '', $trans['item_type']);
                if (isset($summary[$type])) {
                    if ($trans['trans_type'] === 'masuk') {
                        $summary[$type]['total_masuk'] = (int)$trans['total_quantity'];
                    } else if ($trans['trans_type'] === 'keluar') {
                        $summary[$type]['total_keluar'] = (int)$trans['total_quantity'];
                    }
                }
            }
            
            // Calculate final sisa stok
            foreach ($summary as $type => $data) {
                $summary[$type]['sisa_stok'] = $data['current_stock'] - $data['total_keluar'];
            }

            return $summary;

        } catch (\Exception $e) {
            log_message('error', 'Error in getTabungSummary: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return [
                '3kg' => ['total_masuk' => 0, 'total_keluar' => 0, 'current_stock' => 0, 'sisa_stok' => 0],
                '12kg' => ['total_masuk' => 0, 'total_keluar' => 0, 'current_stock' => 0, 'sisa_stok' => 0]
            ];
        }
    }

    public function getIncomingTransactions($limit = 5)
    {
        return $this->select('
                transactions.transaction_date,
                items.name as item_name,
                transactions.quantity,
                transactions.notes,
                COALESCE(suppliers.name, clients.name, "-") as partner_name
            ')
            ->join('items', 'items.id = transactions.item_id')
            ->join('suppliers', 'suppliers.id = transactions.supplier_id', 'left')
            ->join('clients', 'clients.client_id = transactions.client_id', 'left')
            ->where('transactions.type', 'masuk')
            ->orderBy('transactions.transaction_date', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function getTabungTransactions()
    {
        try {
            $builder = $this->db->table($this->table . ' t')
                ->select('t.*, i.name as item_name, w.name as warehouse_name, c.name as client_name')
                ->join('items i', 'i.id = t.item_id')
                ->join('warehouses w', 'w.id = t.warehouse_id')
                ->join('clients c', 'c.client_id = t.client_id', 'left')
                // ->where('i.category', 'tabung')
                ->orderBy('t.created_at', 'DESC');

            $result = $builder->get()->getResultArray();

            // Log untuk debugging
            log_message('debug', 'Query getTabungTransactions: ' . $this->db->getLastQuery());
            log_message('debug', 'Result count: ' . count($result));

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error in getTabungTransactions: ' . $e->getMessage());
            return [];
        }
    }

    public function getTransaksiTabung()
    {
        return $this->select('
                transactions.*,
                items_part.name as item_name,
                warehouses.name as warehouse_name,
                clients.name as client_name
            ')
            ->join('items_part', 'items_part.id = transactions.item_id', 'left')
            ->join('warehouses', 'warehouses.id = transactions.warehouse_id', 'left')
            ->join('clients', 'clients.client_id = transactions.client_id', 'left')
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();
    }
} 