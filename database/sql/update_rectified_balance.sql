CREATE PROCEDURE IF NOT EXISTS  `UpdateRectifiedBalance`()
BEGIN
    -- Variables pour le curseur
    DECLARE
done INT DEFAULT FALSE;
    DECLARE
v_reference VARCHAR(11);
    DECLARE
v_ref VARCHAR(20);
    DECLARE
v_idamount INT;
    DECLARE
v_code VARCHAR(10);
    DECLARE
v_created_at DATETIME;

    -- Variables pour la gestion du nouveau code
    DECLARE
new_code INT DEFAULT 1976;

    -- Déclaration du curseur pour parcourir les lignes
    DECLARE
user_balance_cursor CURSOR FOR
SELECT code, reference, created_at, idamount, ref
FROM rectified_userbalance
WHERE balance_operation_id IN (1, 6, 16, 18, 38, 42, 51)
   OR (balance_operation_id = 44 AND value > 0)
ORDER BY created_at;

-- Gestion de la fin des enregistrements
DECLARE
CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Création ou modification des tables nécessaires
CREATE TABLE IF NOT EXISTS rectified_balances
(
    balance_operation_id
    INT,
    description
    VARCHAR
(
    512
),
    Description_old VARCHAR
(
    512
),
    operator_id VARCHAR
(
    9
),
    beneficiary_id VARCHAR
(
    9
),
    value DOUBLE,
    current_balance DOUBLE,
    reference VARCHAR
(
    18
), -- Définir ici VARCHAR(18)
    ref VARCHAR
(
    100
),
    code INT,
    payed DOUBLE,
    gifted_shares INT,
    PU DOUBLE,
    created_at DATETIME,
    updated_at DATETIME,
    idamount INT
    );
truncate table rectified_balances;

truncate TABLE trait_balances;
insert into trait_balances
SELECT *
FROM rectified_userbalance;



delete
from trait_balances
where ref in ('442405231585', '442405231589', '442405231593', '442405231597', '442404291333', '422409112155')
;

-- Vider la table `rectified_balances` avant les insertions

-- Ouvrir le curseur
OPEN user_balance_cursor;

read_loop
: LOOP
        -- Lire une ligne
        FETCH user_balance_cursor INTO v_code, v_reference, v_created_at, v_idamount,v_ref;

        -- Vérifier la fin des enregistrements
        IF
done THEN
            LEAVE read_loop;
END IF;

        -- Vérifier si `code` n’est pas NULL
        IF
v_code IS NOT NULL THEN
            -- Insérer les lignes avec le même code et mettre à jour le champ `reference`
            INSERT INTO rectified_balances
SELECT balance_operation_id,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       value,
       current_balance,
       CONCAT(r.reference, LPAD(new_code, 7, '0')) AS reference, -- Mise à jour du champ
       ref,
       new_code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       idamount
FROM trait_balances r
WHERE r.code * 1 = v_code * 1
  and (r.reference * 1 = v_reference * 1 or ref * 1 = v_ref * 1)
;

-- Incrémenter le nouveau code pour la prochaine opération
SET
new_code = new_code + 1;
END IF;
END LOOP;

    -- Fermer le curseur
CLOSE user_balance_cursor;

-- Supprimer la table temporaire `trait_balances`

END
