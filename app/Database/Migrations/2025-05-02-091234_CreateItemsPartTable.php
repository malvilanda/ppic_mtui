<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsPartTable extends Migration
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
        $this->forge->createTable('items_part');

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
            [
                'id' => 3,
                'kode' => '4342342323',
                'nama_barang' => 'Sarung tangan',
                'status' => 1,
                'stok' => 15,
                'minimum_stok' => 25,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 4,
                'kode' => '5565354232',
                'nama_barang' => 'Coil',
                'status' => 1,
                'stok' => 20,
                'minimum_stok' => 25,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 5,
                'kode' => '232423245',
                'nama_barang' => 'Cat Penta Arsy Putih',
                'status' => 1,
                'stok' => 50,
                'minimum_stok' => 10,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 6,
                'kode' => '232423245',
                'nama_barang' => 'Cat Penta Arsy merah',
                'status' => 1,
                'stok' => 50,
                'minimum_stok' => 10,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 7,
                'kode' => '232423245',
                'nama_barang' => 'Cat PTM hijau',
                'status' => 1,
                'stok' => 10,
                'minimum_stok' => 10,
                'created_at' => '2025-04-26 10:55:12',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 8,
                'kode' => '3343776676653',
                'nama_barang' => 'Balancer',
                'status' => 1,
                'stok' => 399,
                'minimum_stok' => 50,
                'created_at' => '2025-04-26 22:02:09',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 9,
                'kode' => '3343776676653',
                'nama_barang' => 'valve 3kg',
                'status' => 1,
                'stok' => 1500,
                'minimum_stok' => 500,
                'created_at' => '2025-04-26 22:02:09',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 10,
                'kode' => '3343776676653',
                'nama_barang' => 'valve 20kg',
                'status' => 1,
                'stok' => 750,
                'minimum_stok' => 500,
                'created_at' => '2025-04-26 22:02:09',
                'updated_at' => '2025-05-01 22:28:54'
            ],
            [
                'id' => 12,
                'kode' => '1443256533',
                'nama_barang' => 'Seal Tape',
                'status' => 2,
                'stok' => 100,
                'minimum_stok' => 50,
                'created_at' => '2025-05-01 23:33:04',
                'updated_at' => '2025-05-02 00:40:19'
            ]
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('items_part');
        $builder->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('items_part');
    }
} 