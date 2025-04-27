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
} 