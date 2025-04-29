DELIMITER //

-- Trigger untuk update stok saat insert transaksi bahan baku
CREATE TRIGGER trg_after_insert_trans_bahan_baku
AFTER INSERT ON transactions_bahan_baku
FOR EACH ROW
BEGIN
    IF NEW.type = 'masuk' THEN
        UPDATE items_part 
        SET stok_tersedia = stok_tersedia + NEW.quantity,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.item_id;
    ELSE
        UPDATE items_part 
        SET stok_tersedia = stok_tersedia - NEW.quantity,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.item_id;
    END IF;
END //

-- Trigger untuk update stok saat delete transaksi bahan baku
CREATE TRIGGER trg_after_delete_trans_bahan_baku
AFTER DELETE ON transactions_bahan_baku
FOR EACH ROW
BEGIN
    IF OLD.type = 'masuk' THEN
        UPDATE items_part 
        SET stok_tersedia = stok_tersedia - OLD.quantity,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = OLD.item_id;
    ELSE
        UPDATE items_part 
        SET stok_tersedia = stok_tersedia + OLD.quantity,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = OLD.item_id;
    END IF;
END //

DELIMITER ; 