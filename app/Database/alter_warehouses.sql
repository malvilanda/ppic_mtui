ALTER TABLE `warehouses`
ADD COLUMN `capacity` int(11) NOT NULL DEFAULT 0 AFTER `location`; 