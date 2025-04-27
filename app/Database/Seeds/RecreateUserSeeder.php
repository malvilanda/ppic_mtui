<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RecreateUserSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Backup transactions data
        $transactions = $this->db->table('transactions')->get()->getResultArray();

        // Clear transactions table
        $this->db->table('transactions')->truncate();

        // Drop users table
        $this->db->query('DROP TABLE IF EXISTS users');

        // Create users table with new structure
        $this->db->query("CREATE TABLE users (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
            status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY username (username)
        ) ENGINE=InnoDB");

        // Insert user data
        $data = [
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
            ]
        ];

        $userModel = new \App\Models\UserModel();
        $userModel->insertBatch($data);

        // Restore transactions data if any
        if (!empty($transactions)) {
            $this->db->table('transactions')->insertBatch($transactions);
        }

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
} 