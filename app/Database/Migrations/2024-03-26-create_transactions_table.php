<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'item_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'warehouse_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'client_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'          => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['masuk', 'keluar'],
            ],
            'kategori_tabung' => [
                'type'       => 'VARCHAR',
                'constraint' => 1,
                'null'      => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'transaction_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'delivery_order' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'      => true,
            ],
            'delivery_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'receiver_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'      => true,
            ],
            'receiver_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'      => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('item_id', 'items', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
} 