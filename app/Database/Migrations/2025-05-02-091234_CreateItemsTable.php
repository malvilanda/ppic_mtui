<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsTable extends Migration
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
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'minimum_stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->createTable('items');

        // Insert data awal
        $data = [
            [
                'id' => 1,
                'kode' => '1443256533',
                'nama_barang' => 'Seal Tape',
                'status' => 1,
                'stok' => 985,
                'minimum_stok' => 100,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 23:28:29'
            ],
            [
                'id' => 2,
                'kode' => '2234522232',
                'nama_barang' => 'Neck Ring',
                'status' => 1,
                'stok' => 686,
                'minimum_stok' => 50,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 23:08:42'
            ],
            // ... tambahkan data lainnya
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('items');
        $builder->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('items');
    }
} 