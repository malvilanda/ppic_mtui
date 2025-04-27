<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        // Drop table if exists
        $this->db->query('DROP TABLE IF EXISTS transactions');

        // Create transactions table
        $this->db->query("CREATE TABLE transactions (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            item_id INT(11) UNSIGNED NOT NULL,
            warehouse_id INT(11) UNSIGNED NOT NULL,
            client_id INT(11) UNSIGNED NULL,
            user_id INT(11) UNSIGNED NOT NULL,
            type ENUM('masuk', 'keluar') NOT NULL,
            client_name VARCHAR(255) NULL,
            delivery_location TEXT NULL,
            quantity INT(11) NOT NULL,
            transaction_date TIMESTAMP NULL,
            notes TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            PRIMARY KEY (id),
            INDEX idx_item_id (item_id),
            INDEX idx_warehouse_id (warehouse_id),
            INDEX idx_user_id (user_id),
            INDEX idx_client_id (client_id),
            CONSTRAINT fk_transactions_item_id FOREIGN KEY (item_id) REFERENCES items(id),
            CONSTRAINT fk_transactions_warehouse_id FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
            CONSTRAINT fk_transactions_user_id FOREIGN KEY (user_id) REFERENCES users(id),
            CONSTRAINT fk_transactions_client_id FOREIGN KEY (client_id) REFERENCES clients(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS transactions');
    }
} 