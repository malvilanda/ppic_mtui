<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'mac_address' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
                'null' => true,
            ],
            'location' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'login_time' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['success', 'failed'],
                'default' => 'success',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('login_logs');
    }

    public function down()
    {
        $this->forge->dropTable('login_logs');
    }
} 