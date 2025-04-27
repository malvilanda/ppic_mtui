-- Drop table if exists
DROP TABLE IF EXISTS transactions;

-- Create transactions table
CREATE TABLE transactions (
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
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_item_id (item_id),
    INDEX idx_warehouse_id (warehouse_id),
    INDEX idx_user_id (user_id),
    INDEX idx_client_id (client_id),
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 