<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\WarehouseModel;
use App\Models\TransactionModel;
use App\Models\ClientModel;
use CodeIgniter\Database\ConnectionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class Transaksi extends BaseController
{
    protected $itemModel;
    protected $warehouseModel;
    protected $transactionModel;
    protected $clientModel;
    protected $db;
    protected $dompdf;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->warehouseModel = new WarehouseModel();
        $this->transactionModel = new TransactionModel();
        $this->clientModel = new ClientModel();
        $this->db = \Config\Database::connect();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $this->dompdf = new Dompdf($options);
    }

    public function tabung()
    {
        // Load model yang diperlukan
        $itemModel = new \App\Models\ItemModel();
        $clientModel = new \App\Models\ClientModel();
        $warehouseModel = new \App\Models\WarehouseModel();
        $transactionModel = new \App\Models\TransactionModel();

        // Ambil data untuk form dan riwayat transaksi
        $data = [
            'items' => $itemModel->db->table($itemModel->table2)->get()->getResultArray(), // Menggunakan table2 (items)
            'clients' => $clientModel->findAll(),
            'warehouses' => $warehouseModel->findAll(),
            'transactions' => $transactionModel->getTabungTransactions()
        ];

        // Log untuk debugging
        log_message('info', 'DATA TRANSAKSI: ' . json_encode($data['transactions']));

        return view('transaksi/tabung', $data);
    }

    public function bahanBaku()
    {
        $data = [
            'title' => 'Transaksi Bahan Baku',
            'items' => $this->itemModel->findAll(),
            'warehouses' => $this->warehouseModel->findAll(),
            'transactions' => $this->transactionModel->getRecentTransactions(20)
        ];

        return view('transaksi/bahan_baku', $data);
    }

    public function getClientAddresses($clientId)
    {
        try {
            $addresses = $this->clientModel->getClientAddresses($clientId);
            return $this->response->setJSON([
                'success' => true,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getClientAddresses: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Gagal mengambil data alamat'
            ]);
        }
    }

    public function saveTabung()
    {
        try {
            // Log semua data POST untuk debugging
            log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
            
            // Cek user_id
            if (!session()->get('user_id')) {
                log_message('error', 'user_id tidak ditemukan dalam session');
                return redirect()->back()->withInput()->with('error', 'Sesi telah berakhir, silakan login kembali');
            }

            // Validasi input dasar
            $rules = [
                'item_id' => 'required|numeric',
                'warehouse_id' => 'required|numeric',
                'type' => 'required|in_list[masuk,keluar]',
                'quantity' => 'required|numeric|greater_than[0]',
                'created_by' => 'required'
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'Validasi dasar gagal: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
            }

            $data = [
                'item_id' => $this->request->getPost('item_id'),
                'warehouse_id' => $this->request->getPost('warehouse_id'),
                'type' => $this->request->getPost('type'),
                'quantity' => $this->request->getPost('quantity'),
                'notes' => $this->request->getPost('notes'),
                'user_id' => session()->get('user_id'),
                'created_by' => $this->request->getPost('created_by'),
                'kategori_tabung' => $this->request->getPost('kategori_tabung') ?? 'A'
            ];

            // Validasi stok untuk transaksi keluar
            if ($data['type'] === 'keluar') {
                $item = $this->itemModel->find($data['item_id']);
                if (!$item) {
                    log_message('error', 'Item tidak ditemukan dengan ID: ' . $data['item_id']);
                    return redirect()->back()->withInput()->with('error', 'Item tidak ditemukan');
                }

                if ($item['stock'] < $data['quantity']) {
                    log_message('error', 'Stok tidak mencukupi. Stok tersedia: ' . $item['stock'] . ', Diminta: ' . $data['quantity']);
                    return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi untuk transaksi keluar');
                }

                // Validasi data tambahan untuk transaksi keluar
                $additionalRules = [
                    'delivery_order' => 'required|is_unique[transactions.delivery_order,id,{id}]',
                    'receiver_name' => 'required',
                    'receiver_phone' => 'required',
                    'client_id' => 'required|numeric'
                ];

                $customMessages = [
                    'delivery_order' => [
                        'required' => 'Nomor DO harus diisi',
                        'is_unique' => 'Nomor DO sudah digunakan, silakan gunakan nomor lain'
                    ],
                    'receiver_name' => [
                        'required' => 'Nama penerima harus diisi'
                    ],
                    'receiver_phone' => [
                        'required' => 'Nomor telepon penerima harus diisi'
                    ],
                    'client_id' => [
                        'required' => 'Client harus dipilih',
                        'numeric' => 'Format client tidak valid'
                    ]
                ];

                if (!$this->validate($additionalRules, $customMessages)) {
                    log_message('error', 'Validasi data pengiriman gagal: ' . json_encode($this->validator->getErrors()));
                    return redirect()->back()->withInput()->with('error', implode(', ', $this->validator->getErrors()));
                }

                // Tambahkan data pengiriman
                $data['delivery_order'] = $this->request->getPost('delivery_order');
                $data['receiver_name'] = $this->request->getPost('receiver_name');
                $data['receiver_phone'] = $this->request->getPost('receiver_phone');
                $data['delivery_address'] = $this->request->getPost('delivery_address');
                $data['client_id'] = $this->request->getPost('client_id');
            }

            // Log data final yang akan disimpan
            log_message('info', 'Data final yang akan disimpan: ' . json_encode($data));

            // Simpan transaksi dalam database transaction
            $this->db->transStart();

            // Simpan transaksi
            $transactionId = $this->transactionModel->insert($data);
            if (!$transactionId) {
                log_message('error', 'Gagal menyimpan transaksi ke database');
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi');
            }

            // Update stok
            $success = $this->itemModel->updateStock($data['item_id'], $data['quantity'], $data['type']);
            if (!$success) {
                log_message('error', 'Gagal mengupdate stok item');
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate stok');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                log_message('error', 'Database transaction failed');
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan dalam transaksi database');
            }

            // Redirect sesuai tipe transaksi
            if ($data['type'] === 'keluar') {
                return redirect()->to("transaksi/delivery-order/{$transactionId}")->with('success', 'Transaksi berhasil disimpan');
            }

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan');

        } catch (\Exception $e) {
            log_message('error', 'Error in saveTabung: ' . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }

    private function generateDeliveryOrder($transactionId)
    {
        $transaction = $this->transactionModel->getTransactionDetail($transactionId);
        
        // Load the view into a variable
        $html = view('transaksi/delivery_order', [
            'transaction' => $transaction,
            'company' => [
                'name' => 'PT. MTU Indonesia',
                'address' => 'Jl. Raya Narogong Km. 18.5, Ds. Pasir Angin Kec. Cileungsi Bogor',
                'phone' => '(021) 22950122',
                'email' => 'marketing.support@mtu-indonesia.com'
            ]
        ]);

        // Load dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf;
    }

    public function viewDeliveryOrder($id)
    {
        try {
            log_message('info', 'Attempting to view delivery order with ID: ' . $id);
            
            $transaction = $this->transactionModel->getTransactionDetail($id);
            
            // Debug information
            log_message('debug', 'Transaction data received: ' . json_encode($transaction));
            
            if (!$transaction) {
                log_message('error', 'Transaction not found with ID: ' . $id);
                // Get debug info
                $debugInfo = $this->transactionModel->debugLastQuery();
                log_message('debug', 'Debug info: ' . json_encode($debugInfo));
                
                return redirect()->back()->with('error', 'Delivery order tidak ditemukan');
            }

            if ($transaction['type'] !== 'keluar') {
                log_message('error', 'Invalid transaction type for ID: ' . $id . '. Type: ' . $transaction['type']);
                return redirect()->back()->with('error', 'Bukan merupakan transaksi keluar');
            }

            // Cek kategori tabung
            $item = $this->itemModel->find($transaction['item_id']);
            if (!$item) {
                log_message('error', 'Item not found for transaction ID: ' . $id);
                return redirect()->back()->with('error', 'Data item tidak ditemukan');
            }

            // Default show company data
            $showCompanyData = true;
            
            // Jika item memiliki kategori dan kategori adalah B, sembunyikan data perusahaan
            if (isset($item['kategori_tabung']) && $item['kategori_tabung'] === 'B') {
                $showCompanyData = false;
            }

            $data = [
                'title' => 'Detail Delivery Order',
                'transaction' => $transaction,
                'item' => $item,
                'showCompanyData' => $showCompanyData,
                'company' => [
                    'name' => 'PT. Maju Teknik Utama Indonesia',
                    'address' => 'Jl. Raya Narogong Km. 18.5, Ds. Pasir Angin Kec. Cileungsi Bogor',
                    'phone' => ' 021-22950122',
                    'email' => 'marketing.support@mtu-indonesia.com'
                ]
            ];

            log_message('info', 'Successfully prepared view data for delivery order: ' . $id);
            return view('transaksi/view_delivery_order', $data);

        } catch (\Exception $e) {
            log_message('error', 'Error in viewDeliveryOrder: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Get debug info in case of error
            $debugInfo = $this->transactionModel->debugLastQuery();
            log_message('debug', 'Debug info: ' . json_encode($debugInfo));
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menampilkan delivery order. Error: ' . $e->getMessage());
        }
    }

    public function printDeliveryOrder($id)
    {
        try {
            log_message('debug', 'Mencoba mengambil data transaksi dengan ID: ' . $id);
            
            $transaction = $this->transactionModel->getTransactionDetail($id);
            log_message('debug', 'Data transaksi yang diambil: ' . json_encode($transaction));
            
            if (!$transaction) {
                log_message('error', 'Transaksi tidak ditemukan dengan ID: ' . $id);
                return redirect()->back()->with('error', 'Delivery order tidak ditemukan');
            }

            if ($transaction['type'] !== 'keluar') {
                log_message('error', 'Tipe transaksi tidak valid: ' . $transaction['type']);
                return redirect()->back()->with('error', 'Bukan merupakan transaksi keluar');
            }

            // Cek kategori tabung
            $item = $this->itemModel->find($transaction['item_id']);
            if (!$item) {
                log_message('error', 'Item tidak ditemukan untuk transaksi ID: ' . $id);
                return redirect()->back()->with('error', 'Data item tidak ditemukan');
            }

            // Default show company data
            $showCompanyData = true;
            
            // Jika item memiliki kategori dan kategori adalah B, sembunyikan data perusahaan
            if (isset($transaction['kategori_tabung']) && $transaction['kategori_tabung'] === 'B') {
                $showCompanyData = false;
            }

            $data = [
                'transaction' => $transaction,
                'item' => $item,
                'showCompanyData' => $showCompanyData,
                'company' => [
                    'name' => 'PT. Maju Teknik Utama Indonesia',
                    'address' => 'Jl. Raya Narogong Km. 18.5, Ds. Pasir Angin Kec. Cileungsi Bogor',
                    'phone' => ' 021-22950122',
                    'email' => 'marketing.support@mtu-indonesia.com'
                ]
            ];

            log_message('debug', 'Data yang akan dikirim ke view: ' . json_encode($data));

            // Load HTML content
            $html = view('transaksi/delivery_order', $data);
            $this->dompdf->loadHtml($html);
            
            // Set paper size and orientation
            $this->dompdf->setPaper('A4', 'portrait');
            
            // Render PDF
            $this->dompdf->render();
            
            // Output PDF
            return $this->dompdf->stream("delivery-order-{$id}.pdf", ["Attachment" => false]);

        } catch (\Exception $e) {
            log_message('error', 'Error in printDeliveryOrder: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencetak delivery order: ' . $e->getMessage());
        }
    }

    public function saveBahanBaku()
    {
        $rules = [
            'item_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'type' => 'required|in_list[masuk,keluar]',
            'quantity' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'item_id' => $this->request->getPost('item_id'),
            'warehouse_id' => $this->request->getPost('warehouse_id'),
            'user_id' => session()->get('user_id'), // Sesuaikan dengan sistem autentikasi Anda
            'type' => $this->request->getPost('type'),
            'quantity' => $this->request->getPost('quantity'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->transactionModel->addTransaction($data)) {
            return redirect()->to('transaksi/bahan-baku')->with('success', 'Transaksi berhasil disimpan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi');
    }

    protected function validateData(array $data, $rules = null, array $messages = [], ?string $dbGroup = null): bool
    {
        if ($rules === null) {
            $rules = [
                'item_id' => 'required|numeric',
                'warehouse_id' => 'required|numeric',
                'type' => 'required|in_list[masuk,keluar]',
                'quantity' => 'required|numeric|greater_than[0]',
                'created_by' => 'required'
            ];

            if ($data['type'] === 'keluar') {
                $rules['delivery_order'] = 'required|is_unique[transactions.delivery_order]';
                $rules['receiver_name'] = 'required';
                $rules['receiver_phone'] = 'required';
            }
        }

        return $this->validate($rules, $messages);
    }

    // Method untuk debugging query
    public function debugTransaction($id = null)
    {
        // Very early debugging
        echo "Debug start\n";
        flush();

        // Disable all output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers for plain text output
        header('Content-Type: text/plain');
        header('X-Accel-Buffering: no');
        
        echo "PHP Version: " . phpversion() . "\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n";
        echo "Transaction ID: " . ($id ?? 'not set') . "\n";
        echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
        echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n\n";
        
        try {
            if (!$id) {
                echo "Error: ID transaksi tidak ditemukan\n";
                return;
            }

            // Ambil koneksi database
            $db = \Config\Database::connect();
            echo "Database connected\n";
            
            // Query manual untuk testing
            $sql = "SELECT 
                t.*,
                i.name as item_name,
                w.name as warehouse_name,
                u.name as pic_name,
                IFNULL(c.name, '-') as client_name,
                IFNULL(c.code, '-') as client_code
            FROM transactions t
            JOIN items i ON i.id = t.item_id
            JOIN warehouses w ON w.id = t.warehouse_id
            JOIN users u ON u.id = t.user_id
            LEFT JOIN clients c ON c.id = t.client_id
            WHERE t.id = ?";

            echo "\nQuery SQL:\n" . str_replace('?', $id, $sql) . "\n\n";

            // Eksekusi query
            $query = $db->query($sql, [$id]);
            echo "Query executed\n";
            
            if (!$query) {
                echo "Error: Query gagal dieksekusi\n";
                return;
            }

            $result = $query->getResultArray();
            
            if (empty($result)) {
                echo "Tidak ada data ditemukan untuk ID: $id\n";
            } else {
                echo "\nHasil Query:\n";
                print_r($result);
            }

            echo "\nInformasi Database:\n";
            echo "Database: " . $db->database . "\n";
            echo "Hostname: " . $db->hostname . "\n";
            echo "Username: " . $db->username . "\n";

        } catch (\Exception $e) {
            echo "\nError:\n";
            echo "Message: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
            echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
        }
    }
} 