<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    protected $itemModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $transactionModel = new TransactionModel();
        $itemModel = new ItemModel();
        
        // Get incoming transactions
        $incoming_transactions = $transactionModel->getIncomingTransactions();
        
        // Get raw materials summary
        $bahan_baku_summary = $itemModel->getBahanBakuSummary();
        
        // Get tabung summary with stok bergerak
        $tabung_summary = $transactionModel->getTabungSummary();
        
        // Adjust sisa stok and total keluar based on stok bergerak
        foreach (['3kg', '5kg', '12kg', '15kg'] as $type) {
            if (isset($tabung_summary[$type])) {
                $stok_bergerak = $tabung_summary[$type]['stok_bergerak'] ?? 0;
                $tabung_summary[$type]['sisa_stok'] = ($tabung_summary[$type]['sisa_stok'] ?? 0) + $stok_bergerak;
                $tabung_summary[$type]['total_keluar'] = ($tabung_summary[$type]['total_keluar'] ?? 0) - $stok_bergerak;
            }
        }
        
        $data = [
            'title' => 'Dashboard',
            'stock_summary' => $itemModel->getStockSummary(),
            'tabung_summary' => $tabung_summary,
            'total_transactions' => $transactionModel->getTotalTransactions(),
            'recent_transactions' => $transactionModel->getRecentTransactions(),
            'incoming_transactions' => $incoming_transactions,
            'bahan_baku_summary' => $bahan_baku_summary
        ];
        
        return view('dashboard/index', $data);
    }

    public function getStockData()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get current stock
            $stockQuery = $db->table('items')
                ->select('type, stock')
                ->where('category', 'tabung_produksi')
                ->get();
            
            $stocks = $stockQuery->getResultArray();
            
            // Initialize current stock data
            $currentStock = [
                'tabung_3kg' => 0,
                'tabung_12kg' => 0,
                'tabung_5kg' => 0,
                'tabung_15kg' => 0
            ];
            
            // Set current stock values
            foreach ($stocks as $stock) {
                if (isset($currentStock[$stock['type']])) {
                    $currentStock[$stock['type']] = (int)$stock['stock'];
                }
            }
            
            // Get historical data for last 6 months
            $historicalData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $startDate = date('Y-m-01', strtotime($month));
                $endDate = date('Y-m-t', strtotime($month));
                
                // Get transactions for this month
                $transQuery = $db->table('transactions t')
                    ->select('
                        i.type as item_type,
                        t.type as trans_type,
                        SUM(t.quantity) as total_quantity
                    ')
                    ->join('items i', 'i.id = t.item_id')
                    ->where('i.category', 'tabung_produksi')
                    ->where('DATE(t.transaction_date) >=', $startDate)
                    ->where('DATE(t.transaction_date) <=', $endDate)
                    ->groupBy('i.type, t.type')
                    ->get();
                
                $transactions = $transQuery->getResultArray();
                
                // Initialize month data with current stock
                $monthData = [
                    'tabung_3kg' => 0,
                    'tabung_12kg' => 0,
                    'tabung_5kg' => 0,
                    'tabung_15kg' => 0
                ];
                
                // Calculate total stock for this month
                foreach ($transactions as $trans) {
                    $type = $trans['item_type'];
                    if (isset($monthData[$type])) {
                        if ($trans['trans_type'] === 'masuk') {
                            $monthData[$type] += (int)$trans['total_quantity'];
                        } else {
                            $monthData[$type] -= (int)$trans['total_quantity'];
                        }
                    }
                }
                
                $historicalData[$month] = $monthData;
            }
            
            // Calculate running totals from oldest to newest
            $runningTotal = [
                'tabung_3kg' => 0,
                'tabung_12kg' => 0,
                'tabung_5kg' => 0,
                'tabung_15kg' => 0
            ];
            
            foreach ($historicalData as $month => $data) {
                $runningTotal['tabung_3kg'] += $data['tabung_3kg'];
                $runningTotal['tabung_12kg'] += $data['tabung_12kg'];
                $runningTotal['tabung_5kg'] += $data['tabung_5kg'];
                $runningTotal['tabung_15kg'] += $data['tabung_15kg'];
                
                $historicalData[$month] = [
                    'tabung_3kg' => $runningTotal['tabung_3kg'],
                    'tabung_12kg' => $runningTotal['tabung_12kg'],
                    'tabung_5kg' => $runningTotal['tabung_5kg'],
                    'tabung_15kg' => $runningTotal['tabung_15kg']
                ];
            }
            
            $response = [
                'current' => $currentStock,
                'historical' => $historicalData,
                'labels' => array_keys($historicalData)
            ];
            
            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getStockData: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Failed to get stock data'
            ])->setStatusCode(500);
        }
    }

    public function getActivityLog()
    {
        $activities = $this->transactionModel->getRecentTransactions();
        
        // Format data untuk tampilan
        $formattedActivities = [];
        foreach ($activities as $activity) {
            $formattedActivities[] = [
                'tanggal' => date('Y-m-d H:i', strtotime($activity['transaction_date'])),
                'jenis' => ucfirst($activity['type']),
                'gudang' => $activity['warehouse_name'],
                'pic' => $activity['pic_name'],
                'jumlah' => $activity['quantity']
            ];
        }

        return $this->response->setJSON($formattedActivities);
    }

    public function testTabungSummary()
    {
        $summary = $this->transactionModel->getTabungSummary();
        
        // Get the database connection for debugging
        $db = \Config\Database::connect();
        
        echo "<pre>";
        echo "Current Month: " . date('Y-m') . "\n\n";
        
        echo "SQL Query:\n";
        $builder = $db->table('transactions')
            ->select('
                items.type,
                SUM(CASE WHEN transactions.type = "masuk" THEN transactions.quantity ELSE 0 END) as total_masuk,
                SUM(CASE WHEN transactions.type = "keluar" THEN transactions.quantity ELSE 0 END) as total_keluar,
                items.stock as current_stock
            ')
            ->join('items', 'items.id = transactions.item_id')
            ->where('items.category', 'tabung_produksi')
            ->where('DATE_FORMAT(transactions.transaction_date, "%Y-%m")', date('Y-m'))
            ->groupBy('items.type, items.stock');
        
        echo $builder->getCompiledSelect() . ";\n\n";
        
        echo "Raw Results:\n";
        print_r($summary);
        echo "</pre>";
        
        return;
    }

    public function bahanBaku()
    {
        $itemModel = new ItemModel();
        
        // Get all raw materials
        $bahan_baku = $itemModel->where('category', 'bahan_baku')->findAll();
        
        // Calculate total stock and count items below minimum stock
        $total_stock = 0;
        $low_stock_count = 0;
        
        foreach ($bahan_baku as $item) {
            $total_stock += $item['stock'];
            if ($item['stock'] <= $item['min_stock']) {
                $low_stock_count++;
            }
        }
        
        $data = [
            'title' => 'Detail Stok Bahan Baku',
            'bahan_baku' => $bahan_baku,
            'total_stock' => $total_stock,
            'low_stock_count' => $low_stock_count
        ];
        
        return view('dashboard/bahan_baku', $data);
    }

    // Add method to get raw materials summary
    public function getBahanBakuSummary()
    {
        $itemModel = new ItemModel();
        $summary = $itemModel->getBahanBakuSummary();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $summary
        ]);
    }

    public function bahanBakuDetail()
    {
        $itemModel = new \App\Models\ItemModel();
        $data['items'] = $itemModel->getBahanBakuSummary();
        
        return view('items/bahan_baku_detail', $data);
    }

    public function exportBahanBaku()
    {
        $itemModel = new \App\Models\ItemModel();
        $items = $itemModel->getBahanBakuSummary();
        
        // ... kode export Excel yang sudah ada ...
    }
} 