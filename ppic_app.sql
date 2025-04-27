/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100432
Source Host           : localhost:3306
Source Database       : ppic_app

Target Server Type    : MYSQL
Target Server Version : 100432
File Encoding         : 65001

Date: 2025-04-27 13:20:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pic_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of clients
-- ----------------------------
INSERT INTO `clients` VALUES ('1', 'CLT001', 'PT. Dwi Kafi', 'Jalan Raya Subang No.45', '082122110099', 'agus@gmail.com', 'agus', 'active', '2025-04-25 04:22:02', '2025-04-25 04:22:02');

-- ----------------------------
-- Table structure for delivery_orders
-- ----------------------------
DROP TABLE IF EXISTS `delivery_orders`;
CREATE TABLE `delivery_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_number` varchar(50) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_phone` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `status` enum('pending','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of delivery_orders
-- ----------------------------

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `type` enum('bahan_baku','tabung_3kg','tabung_12kg','tabung_5kg','tabung_15kg') NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category`) REFERENCES `item_categories` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES ('7', 'Tabung Gas 3kg', 'tabung_produksi', 'tabung_3kg', '696', '50', '2025-04-26 10:55:12', '2025-04-26 09:53:01');
INSERT INTO `items` VALUES ('8', 'Tabung Gas 12kg', 'tabung_produksi', 'tabung_12kg', '410', '25', '2025-04-26 10:55:12', '2025-04-26 15:11:20');
INSERT INTO `items` VALUES ('9', 'Tabung Gas 20kg', 'tabung_produksi', 'tabung_15kg', '155', '25', '2025-04-26 10:55:12', '2025-04-27 00:24:31');
INSERT INTO `items` VALUES ('10', 'Tabung Gas 5kg', 'tabung_produksi', 'tabung_5kg', '200', '25', '2025-04-26 10:55:12', '2025-04-26 10:59:44');

-- ----------------------------
-- Table structure for items_part
-- ----------------------------
DROP TABLE IF EXISTS `items_part`;
CREATE TABLE `items_part` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(255) DEFAULT '',
  `name` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of items_part
-- ----------------------------
INSERT INTO `items_part` VALUES ('1', '1443256533', 'Seal Tape', '1250', '100', '2025-04-26 10:55:12', '2025-04-26 21:30:29');
INSERT INTO `items_part` VALUES ('2', '2234522232', 'Neck Ring', '696', '50', '2025-04-26 10:55:12', '2025-04-26 21:31:04');
INSERT INTO `items_part` VALUES ('3', '4342342323', 'Sarung tangan', '15', '25', '2025-04-26 10:55:12', '2025-04-26 21:34:30');
INSERT INTO `items_part` VALUES ('4', '5565354232', 'Coil', '20', '25', '2025-04-26 10:55:12', '2025-04-26 21:34:24');
INSERT INTO `items_part` VALUES ('5', '232423245', 'Cat Penta Arsy Putih', '50', '10', '2025-04-26 10:55:12', '2025-04-26 21:34:12');
INSERT INTO `items_part` VALUES ('6', '232423245', 'Cat Penta Arsy merah', '50', '10', '2025-04-26 10:55:12', '2025-04-26 21:34:12');
INSERT INTO `items_part` VALUES ('7', '232423245', 'Cat PTM hijau', '50', '10', '2025-04-26 10:55:12', '2025-04-26 21:34:12');
INSERT INTO `items_part` VALUES ('8', '3343776676653', 'Balancer', '399', '50', '2025-04-26 22:02:09', '2025-04-26 16:00:38');
INSERT INTO `items_part` VALUES ('9', '3343776676653', 'valve 3kg', '1500', '500', '2025-04-26 22:02:09', '2025-04-26 22:02:09');
INSERT INTO `items_part` VALUES ('10', '3343776676653', 'valve 20kg', '750', '500', '2025-04-26 22:02:09', '2025-04-26 22:02:09');

-- ----------------------------
-- Table structure for item_categories
-- ----------------------------
DROP TABLE IF EXISTS `item_categories`;
CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of item_categories
-- ----------------------------
INSERT INTO `item_categories` VALUES ('1', 'tabung_produksi', 'Tabung Produksi', 'Kategori untuk tabung hasil produksi', '2025-04-26 10:55:12', null);
INSERT INTO `item_categories` VALUES ('2', 'bahan_baku', 'Bahan Baku', 'Kategori untuk bahan baku umum', '2025-04-26 10:55:12', null);

-- ----------------------------
-- Table structure for item_types
-- ----------------------------
DROP TABLE IF EXISTS `item_types`;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of item_types
-- ----------------------------
INSERT INTO `item_types` VALUES ('1', 'tabung_3kg', 'Tabung Gas 3 KG', 'Tabung gas ukuran 3 KG', 'tabung_produksi', '2025-04-25 11:09:47', '2025-04-26 10:46:24');
INSERT INTO `item_types` VALUES ('2', 'tabung_5kg', 'Tabung Gas 5 KG', 'Tabung gas ukuran 5 KG', 'tabung_produksi', '2025-04-25 11:09:47', '2025-04-26 10:46:34');
INSERT INTO `item_types` VALUES ('3', 'tabung_12kg', 'Tabung Gas 12 KG', 'Tabung gas ukuran 12 KG', 'tabung_produksi', '2025-04-25 11:09:47', '2025-04-26 10:46:40');
INSERT INTO `item_types` VALUES ('4', 'tabung_20kg', 'Tabung Gas 20 KG', 'Tabung gas ukuran 15 KG', 'tabung_produksi', '2025-04-25 11:09:47', '2025-04-26 22:13:19');
INSERT INTO `item_types` VALUES ('5', 'coil', 'Coil', 'Bahan baku berupa coil untuk pembuatan tabung', 'bahan_baku', '2025-04-25 11:10:26', null);
INSERT INTO `item_types` VALUES ('6', 'valve', 'Valve', 'Katup (valve) untuk tabung gas', 'bahan_baku', '2025-04-25 11:10:26', null);
INSERT INTO `item_types` VALUES ('7', 'seal_tape', 'Seal Tape', 'Seal tape untuk menyegel sambungan', 'bahan_baku', '2025-04-25 11:10:26', null);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for stock_opname
-- ----------------------------
DROP TABLE IF EXISTS `stock_opname`;
CREATE TABLE `stock_opname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `system_stock` int(11) NOT NULL,
  `actual_stock` int(11) NOT NULL,
  `difference` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `opname_date` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `warehouse_id` (`warehouse_id`),
  CONSTRAINT `stock_opname_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `stock_opname_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of stock_opname
-- ----------------------------

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `type` enum('masuk','keluar') NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_date` timestamp NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `delivery_order` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `receiver_name` varchar(100) DEFAULT NULL,
  `receiver_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_transactions_item` (`item_id`),
  KEY `fk_transactions_warehouse` (`warehouse_id`),
  KEY `fk_transactions_client` (`client_id`),
  KEY `fk_transactions_user` (`user_id`),
  KEY `idx_supplier_id` (`supplier_id`),
  CONSTRAINT `fk_transactions_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES ('20', '7', '1', '1', null, '1', 'keluar', '5', '2025-03-26 04:54:37', 'test', 'pending', '001', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 11:54:37', '2025-04-26 22:18:56');
INSERT INTO `transactions` VALUES ('21', '7', '1', '1', null, '1', 'keluar', '4', '2025-03-26 08:40:58', 'test2', 'pending', '112', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 15:40:58', '2025-04-26 22:19:01');
INSERT INTO `transactions` VALUES ('22', '7', '1', '1', null, '1', 'keluar', '2', '2025-04-26 09:19:21', 'test', 'pending', 'DO/2025/04/0113', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 16:19:21', '2025-04-26 16:19:21');
INSERT INTO `transactions` VALUES ('23', '7', '1', '1', null, '1', 'keluar', '2', '2025-04-26 09:22:59', 'test', 'pending', 'DO/2025/04/0114', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 16:22:59', '2025-04-26 16:22:59');
INSERT INTO `transactions` VALUES ('24', '7', '1', '1', null, '1', 'keluar', '3', '2025-04-26 09:53:01', 'test  aja', 'pending', 'DO/2025/04/0115', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 16:53:01', '2025-04-26 16:53:01');
INSERT INTO `transactions` VALUES ('25', '8', '1', '1', null, '1', 'keluar', '15', '2025-04-26 15:11:20', 'test', 'pending', 'DO/2025/04/0116', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 22:11:20', '2025-04-26 22:11:20');
INSERT INTO `transactions` VALUES ('26', '9', '1', '1', null, '1', 'keluar', '100', '2025-04-26 15:13:56', 'test', 'pending', 'DO/2025/04/0117', 'Jalan Raya Subang No.45', 'agus', '082122110099', '2025-04-26 22:13:56', '2025-04-26 22:13:56');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','staff','supervisor','manager','gen.manager') NOT NULL DEFAULT 'staff',
  `status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', '$2y$10$lfQi4QJ45uadfOXBQfZWNuDnmOhRTM1Qhq47OLzQtajuBubwyDEUS', 'Administrator', 'admin', '1', '2025-04-25 05:54:05', '2025-04-25 05:54:05');
INSERT INTO `users` VALUES ('2', 'ahmad', '$2y$10$5IhQlYKBvMUbnUv31XQgN.ZNYh6oxb1O2jfH/Mk/TDyOvO5GY.TWW', 'Ahmad Staff', 'staff', '1', '2025-04-25 05:54:05', '2025-04-25 05:54:05');
INSERT INTO `users` VALUES ('3', 'budi', '$2y$10$s60PPjUUyruT10//RqckrOlH0bkFOpItkSmWBmPcfOsvFeFJ./D2m', 'Budi Staff', 'staff', '1', '2025-04-25 05:54:05', '2025-04-25 05:54:05');
INSERT INTO `users` VALUES ('4', 'kiki', '$2y$10$ALqFjEb06M7a0to0omW13eYSBjfUConV2Ea85CedZLdy2xP4lyTz2', '', 'supervisor', '1', '2025-04-26 19:17:59', '2025-04-26 19:17:59');
INSERT INTO `users` VALUES ('5', 'erik', '$2y$10$XZnJdpHQK/ylwbuDWbPpz.pml5P70t7LPlHgTlp6qfk1f2P7etLou', '', 'staff', '1', '2025-04-26 19:24:32', '2025-04-26 19:24:32');

-- ----------------------------
-- Table structure for warehouses
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of warehouses
-- ----------------------------
INSERT INTO `warehouses` VALUES ('1', 'Gudang Narogong', 'Jl. Raya Narogong', '10000', '2025-04-25 10:33:18');
INSERT INTO `warehouses` VALUES ('2', 'Gudang Karawang', 'Jl. Cikarang - Karawang', '10000', '2025-04-25 10:33:18');

-- ----------------------------
-- Table structure for warehouse_items
-- ----------------------------
DROP TABLE IF EXISTS `warehouse_items`;
CREATE TABLE `warehouse_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `warehouse_items_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `warehouse_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of warehouse_items
-- ----------------------------
