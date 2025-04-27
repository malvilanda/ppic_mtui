-- Step 2: Add indexes
ALTER TABLE transactions ADD INDEX idx_item_id (item_id);
ALTER TABLE transactions ADD INDEX idx_warehouse_id (warehouse_id);
ALTER TABLE transactions ADD INDEX idx_user_id (user_id);
ALTER TABLE transactions ADD INDEX idx_client_id (client_id);

-- Step 3: Add foreign keys one by one
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
REFERENCES clients(id) 
ON DELETE SET NULL 
ON UPDATE CASCADE; 