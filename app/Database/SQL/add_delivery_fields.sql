-- Add delivery-related columns to transactions table
ALTER TABLE transactions 
ADD COLUMN delivery_order VARCHAR(50) NULL AFTER notes,
ADD COLUMN delivery_address TEXT NULL AFTER delivery_order,
ADD COLUMN receiver_name VARCHAR(100) NULL AFTER delivery_address,
ADD COLUMN receiver_phone VARCHAR(20) NULL AFTER receiver_name; 