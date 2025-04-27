<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'name'     => 'Administrator',
            'role'     => 'admin',
            'status'   => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Cek apakah user admin sudah ada
        $userModel = new \App\Models\UserModel();
        if (!$userModel->where('username', 'admin')->first()) {
            $userModel->insert($data);
        }
    }
} 