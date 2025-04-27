<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyTransactionsUsers extends Migration
{
    public function up()
    {
        // Drop foreign key if exists
        $this->db->query('ALTER TABLE transactions DROP FOREIGN KEY IF EXISTS transactions_user_id_foreign');
        
        // Drop users table
        $this->forge->dropTable('users', true);

        // Create users table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'staff'],
                'default' => 'staff',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // Add foreign key back
        $this->db->query('ALTER TABLE transactions ADD CONSTRAINT transactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE transactions DROP FOREIGN KEY transactions_user_id_foreign');
        $this->forge->dropTable('users', true);
    }
} 