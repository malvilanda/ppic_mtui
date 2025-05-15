<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 
        'email', 
        'password',
        'remember_token',
        'remember_token_expires_at',
        'name',
        'role',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => [
            'rules'  => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
            'errors' => [
                'required'   => 'Username harus diisi.',
                'min_length' => 'Username minimal 3 karakter.',
                'max_length' => 'Username maksimal 100 karakter.',
                'is_unique'  => 'Username sudah digunakan.'
            ]
        ],
        'email' => [
            'rules'  => 'permit_empty|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
            'errors' => [
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 255 karakter.',
                'is_unique'   => 'Email sudah digunakan.'
            ]
        ],
        'password' => [
            'rules'  => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'errors' => [
                'required'     => 'Password harus diisi.',
                'min_length'   => 'Password minimal 8 karakter.',
                'regex_match'  => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.'
            ]
        ],
        'name' => [
            'rules'  => 'permit_empty|max_length[100]',
            'errors' => [
                'max_length' => 'Nama maksimal 100 karakter.'
            ]
        ],
        'role' => [
            'rules'  => 'required|in_list[admin,staff,supervisor,manager,gen.manager]',
            'errors' => [
                'required' => 'Role harus dipilih.',
                'in_list'  => 'Role tidak valid.'
            ]
        ],
        'status' => [
            'rules'  => 'permit_empty|in_list[0,1]',
            'errors' => [
                'in_list' => 'Status tidak valid.'
            ]
        ]
    ];

    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        // Debug log sebelum proses
        log_message('debug', '[hashPassword] Data sebelum proses: ' . print_r($data, true));
        
        if (isset($data['data']['password'])) {
            // Simpan data lain terlebih dahulu
            $email = $data['data']['email'] ?? null;
            $name = $data['data']['name'] ?? null;
            
            // Hash password
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
            
            // Kembalikan data lain
            if ($email !== null) {
                $data['data']['email'] = $email;
            }
            if ($name !== null) {
                $data['data']['name'] = $name;
            }
        }
        
        // Debug log setelah proses
        log_message('debug', '[hashPassword] Data setelah proses: ' . print_r($data, true));
        
        return $data;
    }

    public function createUser($data)
    {
        // Debug log
        log_message('debug', '[UserModel::createUser] Input data:');
        log_message('debug', print_r($data, true));

        try {
            // Hash password jika belum di-hash
            if (isset($data['password']) && !str_starts_with($data['password'], '$2y$')) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            // Nonaktifkan callback untuk mencegah double hashing
            $this->beforeInsert = [];
            
            // Insert data
            $userId = $this->insert($data, true);
            
            // Debug log
            log_message('debug', '[UserModel::createUser] Insert result:');
            log_message('debug', '- User ID: ' . ($userId ?: 'false'));
            
            if ($userId === false) {
                log_message('error', '[UserModel::createUser] Insert errors:');
                log_message('error', print_r($this->errors(), true));
            }

            return $userId;
            
        } catch (\Exception $e) {
            log_message('error', '[UserModel::createUser] Exception: ' . $e->getMessage());
            return false;
        } finally {
            // Kembalikan callback
            $this->beforeInsert = ['hashPassword'];
        }
    }

    public function verifyPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }
} 