<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockHistory extends Migration
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
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'date' => [
                'type' => 'DATETIME',
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
        $this->forge->createTable('stock_history');
    }

    public function down()
    {
        $this->forge->dropTable('stock_history');
    }
} 