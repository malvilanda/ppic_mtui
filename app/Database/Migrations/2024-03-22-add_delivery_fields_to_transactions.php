<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryFieldsToTransactions extends Migration
{
    public function up()
    {
        $sql = "ALTER TABLE transactions 
                ADD COLUMN warehouse_id INT NOT NULL AFTER notes,
                ADD COLUMN delivery_order VARCHAR(50) NULL AFTER warehouse_id,
                ADD COLUMN delivery_address TEXT NULL AFTER delivery_order,
                ADD COLUMN receiver_name VARCHAR(100) NULL AFTER delivery_address,
                ADD COLUMN receiver_phone VARCHAR(20) NULL AFTER receiver_name";
                
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE transactions 
                DROP COLUMN warehouse_id,
                DROP COLUMN delivery_order,
                DROP COLUMN delivery_address,
                DROP COLUMN receiver_name,
                DROP COLUMN receiver_phone";
                
        $this->db->query($sql);
    }
} 