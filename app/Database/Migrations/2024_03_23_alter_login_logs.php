<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterLoginLogs extends Migration
{
    public function up()
    {
        // Tambah kolom last_activity dan is_active
        $sql = "ALTER TABLE `login_logs`
            ADD COLUMN `last_activity` datetime DEFAULT NULL AFTER `login_time`,
            ADD COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `status`,
            MODIFY COLUMN `status` enum('success','failed','logout') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'success';";

        $this->db->query($sql);
    }

    public function down()
    {
        // Hapus kolom yang ditambahkan
        $sql = "ALTER TABLE `login_logs`
            DROP COLUMN `last_activity`,
            DROP COLUMN `is_active`,
            MODIFY COLUMN `status` enum('success','failed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'success';";
            
        $this->db->query($sql);
    }
} 