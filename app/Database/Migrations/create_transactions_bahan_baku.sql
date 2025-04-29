-- Buat tabel untuk transaksi bahan baku
CREATE TABLE transactions_bahan_baku (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    item_id INT(11) NOT NULL,
    warehouse_id INT(11) NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    unit_id INT(11) UNSIGNED NULL,
    type ENUM('masuk', 'keluar') NOT NULL,
    quantity INT(11) NOT NULL,
    transaction_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_item_id (item_id),
    INDEX idx_warehouse_id (warehouse_id),
    INDEX idx_user_id (user_id),
    INDEX idx_unit_id (unit_id),
    CONSTRAINT fk_trans_bb_item FOREIGN KEY (item_id) REFERENCES items_part(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_trans_bb_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_trans_bb_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_trans_bb_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- View untuk melihat transaksi bahan baku
CREATE OR REPLACE VIEW v_transaksi_bahan_baku AS
SELECT 
    t.id,
    t.transaction_date,
    t.type,
    t.quantity,
    t.notes,
    ip.name as item_name,
    ip.part_number,
    ip.satuan,
    w.name as warehouse_name,
    u.name as pic_name,
    un.name as unit_name,
    t.created_at,
    t.updated_at
FROM transactions_bahan_baku t
INNER JOIN items_part ip ON t.item_id = ip.id
INNER JOIN warehouses w ON t.warehouse_id = w.id
INNER JOIN users u ON t.user_id = u.id
LEFT JOIN units un ON t.unit_id = un.id
ORDER BY t.transaction_date DESC;

-- View untuk melihat stok bahan baku per gudang
CREATE OR REPLACE VIEW v_stok_bahan_baku AS
SELECT 
    ip.id as item_id,
    ip.name as item_name,
    ip.part_number,
    ip.satuan,
    w.id as warehouse_id,
    w.name as warehouse_name,
    COALESCE(SUM(CASE WHEN t.type = 'masuk' THEN t.quantity ELSE 0 END), 0) as total_masuk,
    COALESCE(SUM(CASE WHEN t.type = 'keluar' THEN t.quantity ELSE 0 END), 0) as total_keluar,
    COALESCE(SUM(CASE WHEN t.type = 'masuk' THEN t.quantity ELSE -t.quantity END), 0) as stok_tersedia
FROM items_part ip
CROSS JOIN warehouses w
LEFT JOIN transactions_bahan_baku t ON t.item_id = ip.id AND t.warehouse_id = w.id
GROUP BY ip.id, w.id; 