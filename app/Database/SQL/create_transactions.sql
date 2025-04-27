-- Create transactions table without foreign key constraints first
CREATE TABLE transactions (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    item_id INT(11) UNSIGNED NOT NULL,
    warehouse_id INT(11) UNSIGNED NOT NULL,
    client_id INT(11) UNSIGNED NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    type ENUM('masuk', 'keluar') NOT NULL,
    quantity INT(11) NOT NULL,
    transaction_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NULL,
    delivery_order VARCHAR(50) NULL,
    delivery_address TEXT NULL,
    receiver_name VARCHAR(100) NULL,
    receiver_phone VARCHAR(20) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes
ALTER TABLE transactions
ADD INDEX idx_item_id (item_id),
ADD INDEX idx_warehouse_id (warehouse_id),
ADD INDEX idx_user_id (user_id),
ADD INDEX idx_client_id (client_id);

-- Add foreign key constraints one by one
ALTER TABLE transactions
ADD CONSTRAINT fk_transactions_item_id 
FOREIGN KEY (item_id) 
REFERENCES items(id) 
ON DELETE RESTRICT 
ON UPDATE CASCADE;

ALTER TABLE transactions
ADD CONSTRAINT fk_transactions_warehouse_id 
FOREIGN KEY (warehouse_id) 
REFERENCES warehouses(id) 
ON DELETE RESTRICT 
ON UPDATE CASCADE;

ALTER TABLE transactions
ADD CONSTRAINT fk_transactions_user_id 
FOREIGN KEY (user_id) 
REFERENCES users(id) 
ON DELETE RESTRICT 
ON UPDATE CASCADE;

ALTER TABLE transactions
ADD CONSTRAINT fk_transactions_client_id 
FOREIGN KEY (client_id) 
REFERENCES clients(client_id) 
ON DELETE RESTRICT 
ON UPDATE CASCADE; 