<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'name'
            ]
        ]);

        // Update existing records
        $this->db->query("UPDATE items SET category = 'bahan_baku' WHERE type LIKE 'bahan_%'");
        $this->db->query("UPDATE items SET category = 'tabung_produksi' WHERE type IN ('tabung_3kg', 'tabung_12kg')");
        $this->db->query("UPDATE items SET category = 'tabung_bahan_baku' WHERE type LIKE 'tabung_bahan_%'");
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'category');
    }
} 