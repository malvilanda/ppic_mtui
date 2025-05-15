<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        
        // Pastikan user sudah login dan memiliki role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Akses ditolak.');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];

        return view('administrator/users', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah User Baru'
        ];

        return view('administrator/add_user', $data);
    }

    public function save()
    {
        // Debug: Log raw request data
        log_message('debug', '==== START USER SAVE DEBUG ====');
        log_message('debug', '[1] POST Data:');
        foreach ($_POST as $key => $value) {
            log_message('debug', "- {$key}: " . (empty($value) ? 'EMPTY' : $value));
        }

        // Validasi input
        if (!$this->validate($this->userModel->validationRules)) {
            log_message('error', '[3] Input validation failed:');
            log_message('error', print_r($this->validator->getErrors(), true));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Siapkan data
            $data = [
                'username' => trim($this->request->getPost('username')),
                'email' => trim($this->request->getPost('email')) ?: null,
                'password' => $this->request->getPost('password'),
                'name' => trim($this->request->getPost('name')) ?: null,
                'role' => $this->request->getPost('role'),
                'status' => $this->request->getPost('status') ?? 1
            ];

            // Debug data yang akan disimpan
            log_message('debug', '[4] Data to be saved:');
            foreach ($data as $key => $value) {
                if ($key !== 'password') { // Jangan log password
                    log_message('debug', "- {$key}: " . (is_null($value) ? 'NULL' : $value));
                }
            }

            // Simpan menggunakan createUser
            $userId = $this->userModel->createUser($data);

            if ($userId === false) {
                log_message('error', '[5] Insert failed. Model errors:');
                log_message('error', print_r($this->userModel->errors(), true));
                throw new \Exception('Gagal menyimpan data user.');
            }

            // Verifikasi data tersimpan
            $savedUser = $this->userModel->find($userId);
            log_message('debug', '[6] Saved user data:');
            foreach ($savedUser as $key => $value) {
                if ($key !== 'password') { // Jangan log password
                    log_message('debug', "- {$key}: " . (is_null($value) ? 'NULL' : $value));
                }
            }

            return redirect()->to('administrator/users')
                           ->with('message', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            log_message('error', '[ERROR] Exception: ' . $e->getMessage());
            log_message('error', '[ERROR] Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()
                           ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('administrator/users')->with('error', 'ID User tidak valid.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('administrator/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];

        return view('administrator/edit_user', $data);
    }

    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('administrator/users')->with('error', 'ID User tidak valid.');
        }

        // Debug: Log data yang diterima
        log_message('debug', 'Update POST Data: ' . print_r($this->request->getPost(), true));

        // Validasi input
        $rules = $this->userModel->validationRules;
        
        // Jika password kosong, tidak perlu divalidasi
        if (!$this->request->getPost('password')) {
            unset($rules['password']);
        }

        if (!$this->validate($rules)) {
            // Debug: Log error validasi
            log_message('debug', 'Update Validation Errors: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Siapkan data
        $data = [
            'username' => trim($this->request->getPost('username')),
            'email' => trim($this->request->getPost('email')) ?: null,
            'name' => trim($this->request->getPost('name')),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status') ?? 1
        ];

        // Jika password diisi, tambahkan ke data
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Debug: Log data yang akan diupdate
        log_message('debug', 'Data to be updated: ' . print_r($data, true));

        try {
            // Update data
            if (!$this->userModel->update($id, $data)) {
                // Debug: Log error dari model
                log_message('error', 'Update Model Errors: ' . print_r($this->userModel->errors(), true));
                throw new \Exception('Gagal mengupdate data user.');
            }

            return redirect()->to('administrator/users')
                           ->with('message', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            log_message('error', '[Users::update] Error: ' . $e->getMessage());
            
            return redirect()->back()->withInput()
                           ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('administrator/users')->with('error', 'ID User tidak valid.');
        }

        try {
            $this->userModel->delete($id);
            return redirect()->to('administrator/users')->with('message', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            log_message('error', '[Users::delete] Error: ' . $e->getMessage());
            return redirect()->to('administrator/users')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
} 