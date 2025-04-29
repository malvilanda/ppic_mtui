<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UnitsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Unit Painting',
                'code' => 'PAINT',
                'description' => 'Unit untuk proses pengecatan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Unit Las',
                'code' => 'WELD',
                'description' => 'Unit untuk proses pengelasan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Unit Die Cast',
                'code' => 'DCAST',
                'description' => 'Unit untuk proses die casting',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Unit Assembly',
                'code' => 'ASMB',
                'description' => 'Unit untuk proses perakitan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Unit Quality Control',
                'code' => 'QC',
                'description' => 'Unit untuk pemeriksaan kualitas',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Unit Machining',
                'code' => 'MACH',
                'description' => 'Unit untuk proses pemesinan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('units')->insertBatch($data);
    }
} 