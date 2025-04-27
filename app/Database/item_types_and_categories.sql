-- Create item_categories table
CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create item_types table
CREATE TABLE `item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `category` (`category`),
  CONSTRAINT `item_types_ibfk_1` FOREIGN KEY (`category`) REFERENCES `item_categories` (`code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default categories
INSERT INTO `item_categories` (`code`, `name`, `description`) VALUES
('tabung_produksi', 'Tabung Produksi', 'Kategori untuk tabung hasil produksi'),
('bahan_baku', 'Bahan Baku', 'Kategori untuk bahan baku pembuatan tabung');

-- Insert default types for tabung_produksi
INSERT INTO `item_types` (`code`, `name`, `category`, `description`) VALUES
('tabung_3kg', 'Tabung Gas 3 KG', 'tabung_produksi', 'Tabung gas ukuran 3 KG'),
('tabung_5kg', 'Tabung Gas 5 KG', 'tabung_produksi', 'Tabung gas ukuran 5 KG'),
('tabung_12kg', 'Tabung Gas 12 KG', 'tabung_produksi', 'Tabung gas ukuran 12 KG'),
('tabung_15kg', 'Tabung Gas 15 KG', 'tabung_produksi', 'Tabung gas ukuran 15 KG');

-- Insert default types for bahan_baku
INSERT INTO `item_types` (`code`, `name`, `category`, `description`) VALUES
('coil', 'Coil', 'bahan_baku', 'Bahan baku berupa coil untuk pembuatan tabung'),
('valve', 'Valve', 'bahan_baku', 'Katup (valve) untuk tabung gas'),
('seal_tape', 'Seal Tape', 'bahan_baku', 'Seal tape untuk menyegel sambungan'); 