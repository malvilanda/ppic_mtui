<?php

namespace App\Controllers;

use App\Models\UserModel;

class Administrator extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function users()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];

        return view('administrator/users', $data);
    }

    public function add_user()
    {
        $data = [
            'title' => 'Tambah User Baru',
            'validation' => \Config\Services::validation()
        ];

        return view('administrator/add_user', $data);
    }

    public function save_user()
    {
        // Validasi input
        $rules = [
            'username' => [
                'rules' => 'required|min_length[4]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 4 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role harus dipilih'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Hash password menggunakan bcrypt
            $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);

            // Data yang akan disimpan
            $data = [
                'username' => $this->request->getPost('username'),
                'password' => $password,
                'role' => $this->request->getPost('role'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Debug: Tampilkan data yang akan disimpan
            log_message('debug', 'Data yang akan disimpan: ' . json_encode($data));

            // Gunakan Query Builder langsung
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            
            // Coba simpan data
            $inserted = $builder->insert($data);

            if ($inserted) {
                // Debug: Tampilkan query terakhir
                log_message('info', 'Query terakhir: ' . $db->getLastQuery());
                log_message('info', 'Insert ID: ' . $db->insertID());
                
                return redirect()->to('administrator/users')
                    ->with('success', 'User berhasil ditambahkan');
            } else {
                // Debug: Tampilkan error database
                $error = $db->error();
                log_message('error', 'Error Database: ' . json_encode($error));
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan data: ' . ($error['message'] ?: 'Unknown error'));
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function userHistory()
    {
        $userHistoryModel = new \App\Models\UserHistoryModel();
        
        $data = [
            'title' => 'Riwayat User',
            'history' => $userHistoryModel->getUserHistory()
        ];
        
        return view('administrator/user_history', $data);
    }

    public function exportUserHistory()
    {
        $userHistoryModel = new \App\Models\UserHistoryModel();
        $history = $userHistoryModel->getUserHistory();
        
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Waktu Login');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'IP Address');
        $sheet->setCellValue('F1', 'MAC Address');
        $sheet->setCellValue('G1', 'Lokasi');
        $sheet->setCellValue('H1', 'Browser');
        
        // Style the header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        // Populate data
        $row = 2;
        foreach ($history as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i:s', strtotime($item['login_time'])));
            $sheet->setCellValue('C' . $row, $item['username']);
            $sheet->setCellValue('D' . $row, $item['status'] == 'success' ? 'Berhasil' : 'Gagal');
            $sheet->setCellValue('E' . $row, $item['ip_address']);
            $sheet->setCellValue('F' . $row, $item['mac_address'] ?? '-');
            $sheet->setCellValue('G' . $row, $item['location'] ?? '-');
            $sheet->setCellValue('H' . $row, $item['user_agent'] ?? '-');
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create writer and output file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="riwayat_login_user.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
} 