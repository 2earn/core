CREATE PROCEDURE IF NOT EXISTS  `UpdateCurrentBalanceshares`()
BEGIN
    DECLARE
done INT DEFAULT FALSE;
    DECLARE
trans_id INT;
    DECLARE
op_id INT;
    DECLARE
trans_value DOUBLE;
    DECLARE
trans_balance DOUBLE;
    DECLARE
trans_user_id VARCHAR(9);
    DECLARE
io_value CHAR(2);
    DECLARE
last_balance DOUBLE;

    -- Curseur pour parcourir les transactions par utilisateur
    DECLARE
cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id
FROM user_balances_shares
ORDER BY beneficiary_id, created_at, balance_operation_id ASC;

-- Gestion de la fin du curseur
DECLARE
CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Temporaire pour stocker le dernier solde par utilisateur
    CREATE
TEMPORARY TABLE IF NOT EXISTS temp_user_balance (
                                                               beneficiary_id VARCHAR(9) PRIMARY KEY,
                                                               current_balance DOUBLE DEFAULT 0
    );

    -- Initialisation des soldes utilisateurs
INSERT INTO temp_user_balance (beneficiary_id, current_balance)
SELECT DISTINCT beneficiary_id, 0
FROM user_balances_shares;

-- Parcourir les transactions
OPEN cur;

read_loop
: LOOP
        FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
        IF
done THEN
            LEAVE read_loop;
END IF;

        -- Récupérer le dernier solde de l'utilisateur
SELECT current_balance
INTO last_balance
FROM temp_user_balance
WHERE beneficiary_id = trans_user_id;

-- Récupérer la valeur de IO (entrée ou sortie) depuis la table operations
SELECT io
INTO io_value
FROM balance_operations
WHERE id = op_id;

-- Calculer le nouveau solde provisoire
IF io_value = 'I' THEN
            SET last_balance = ROUND(last_balance + trans_value, 3);
        ELSEIF
io_value = 'O' THEN
            SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;

        -- Vérifier si le nouveau solde est négatif
        IF
last_balance >= 0 THEN
            -- Mise à jour du solde de la transaction
UPDATE user_balances_shares
SET current_balance = last_balance
WHERE id = trans_id;

-- Mise à jour du dernier solde de l'utilisateur
UPDATE temp_user_balance
SET current_balance = last_balance
WHERE beneficiary_id = trans_user_id;
END IF;
END LOOP;

CLOSE cur;

-- Supprimer la table temporaire
DROP
TEMPORARY TABLE temp_user_balance;
END
