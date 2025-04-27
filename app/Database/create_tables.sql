-- Tabel untuk menyimpan data barang/item
CREATE TABLE items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type ENUM('bahan_baku', 'tabung_3kg', 'tabung_12kg') NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    minimum_stock INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan data gudang
CREATE TABLE warehouses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan data PIC/pengguna
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan transaksi
CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('masuk', 'keluar') NOT NULL,
    quantity INT NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (item_id) REFERENCES items(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert data awal untuk testing
INSERT INTO warehouses (name, location) VALUES
('Gudang A', 'Lokasi A'),
('Gudang B', 'Lokasi B');

INSERT INTO users (username, name, role) VALUES
('admin', 'Administrator', 'admin'),
('ahmad', 'Ahmad Staff', 'staff'),
('budi', 'Budi Staff', 'staff');

INSERT INTO items (name, type, stock, minimum_stock) VALUES
('Bahan Baku Tabung', 'bahan_baku', 1250, 100),
('Tabung Gas 3kg', 'tabung_3kg', 850, 50),
('Tabung Gas 12kg', 'tabung_12kg', 425, 25);

-- Insert sample transactions
INSERT INTO transactions (item_id, warehouse_id, user_id, type, quantity, notes) VALUES
(2, 1, 2, 'masuk', 50, 'Pengisian stok tabung 3kg'),
(3, 2, 3, 'keluar', 30, 'Pengiriman tabung 12kg'); 