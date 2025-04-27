ALTER TABLE `transactions`
ADD COLUMN `client_name` varchar(100) DEFAULT NULL AFTER `type`,
ADD COLUMN `delivery_location` text DEFAULT NULL AFTER `client_name`; 