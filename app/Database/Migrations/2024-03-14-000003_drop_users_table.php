<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUsersTable extends Migration
{
    public function up()
    {
        // Drop the table if it exists
        $this->forge->dropTable('users', true);
    }

    public function down()
    {
        // We don't need to do anything here since we're dropping the table
    }
} 