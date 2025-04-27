-- Step 1: Create base table without foreign keys
DROP TABLE IF EXISTS transactions;

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
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 