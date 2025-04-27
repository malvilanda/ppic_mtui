<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        
        // Hapus data user yang ada
        $this->db->table('users')->emptyTable();

        // Data users baru
        $users = [
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'name'     => 'Administrator',
                'role'     => 'admin',
                'status'   => 'active',
                'created_at' => '2025-04-25 10:33:18',
                'updated_at' => '2025-04-25 10:33:18'
            ],
            [
                'username' => 'ahmad',
                'password' => password_hash('ahmad123', PASSWORD_BCRYPT),
                'name'     => 'Ahmad Staff',
                'role'     => 'staff',
                'status'   => 'active',
                'created_at' => '2025-04-25 10:33:18',
                'updated_at' => '2025-04-25 10:33:18'
            ],
            [
                'username' => 'budi',
                'password' => password_hash('budi123', PASSWORD_BCRYPT),
                'name'     => 'Budi Staff',
                'role'     => 'staff',
                'status'   => 'active',
                'created_at' => '2025-04-25 10:33:18',
                'updated_at' => '2025-04-25 10:33:18'
            ],
        ];

        // Insert batch data
        $userModel->insertBatch($users);
    }
} 