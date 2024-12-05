CREATE PROCEDURE `UpdateRectifiedBalance`()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_reference VARCHAR(11);
    DECLARE v_ref VARCHAR(20);
    DECLARE v_idamount INT;
    DECLARE v_code VARCHAR(10);
    DECLARE v_created_at DATETIME;
    DECLARE new_code INT DEFAULT 1976;
    DECLARE user_balance_cursor CURSOR FOR
SELECT code, reference, created_at, idamount, ref
FROM rectified_userbalance
WHERE balance_operation_id IN (1, 6, 16, 18, 38, 42, 51)
   OR (balance_operation_id = 44 AND value > 0)
ORDER BY created_at;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
drop table IF EXISTS rectified_balances;
drop table IF EXISTS trait_balances;
DROP VIEW IF EXISTS filtred_userbalance;
DROP VIEW IF EXISTS rectified_userbalance;
DROP VIEW IF EXISTS user_infos;
TRUNCATE TABLE `debug_log`;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 23', current_timestamp());
CREATE TABLE IF NOT EXISTS rectified_balances
(
    balance_operation_id INT,
    description          VARCHAR(512),
    Description_old      VARCHAR(512),
    operator_id          VARCHAR(9),
    beneficiary_id       VARCHAR(9),
    beneficiary_id_auto  int                            not null,
    value                DOUBLE,
    current_balance      DOUBLE DEFAULT 0,
    reference            VARCHAR(18),
    ref                  VARCHAR(100),
    code                 INT,
    payed                DOUBLE,
    gifted_shares        INT,
    PU                   DOUBLE,
    created_at           DATETIME,
    updated_at           DATETIME,
    idamount             INT
    );
create or replace view user_infos as
select `u`.`idUser`           AS `idUser`,
       `u`.`id`           AS `iduser_auto`,
       `u`.`idCountry`        AS `idcountry`,
       `u`.`email`            AS `email`,
       `u`.`fullphone_number` AS `fullphone_number`,
       `u`.`internationalID`  AS `internationalid`,
       `m`.`arFirstName`      AS `arfirstname`,
       `m`.`arLastName`       AS `arlastname`,
       `m`.`enFirstName`      AS `enfirstname`,
       `m`.`enLastName`       AS `enlastname`,
       `m`.`nationalID`       AS `nationalid`,
       `m`.`adresse`          AS `adresse`
from `users` `u`
         join `metta_users` `m`
where `u`.`idUser` = `m`.`idUser`;
create or replace view filtred_userbalance as
select b.`id`                  AS `id`,
       `id_item`             AS `id_item`,
       `item_title`          AS `item_title`,
       `id_plateform`        AS `id_plateform`,
       `Date`                AS `Date`,
       `idBalancesOperation` AS `idBalancesOperation`,
       `Description`         AS `Description`,
       `idSource`            AS `idSource`,
       b.`idUser`              AS `idUser`,
       u.`id`              AS `idUser_auto`,
       `idamount`            AS `idamount`,
       `value`               AS `value`,
       `Balance`             AS `Balance`,
       `WinPurchaseAmount`   AS `WinPurchaseAmount`,
       `PU`                  AS `PU`,
       `gifted_shares`       AS `gifted_shares`,
       `Block_trait`         AS `Block_trait`,
       `ref`                 AS `ref`,
       `PrixUnitaire`        AS `PrixUnitaire`
from user_balances b,
     users u
where u.idUser=b.idUser
  and
    ( `value` <> 0
        or (`idamount` = 6 and
            `value` + `gifted_shares` > 0));
create or replace view rectified_userbalance as
select case
           when `f`.`Description` = 'action5$' then 53
           else `f`.`idBalancesOperation` end                                       AS `balance_operation_id`,
       `f`.`Description`                                                            AS `description`,
       `f`.`Description`                                                            AS `Description_old`,
       `f`.`idSource`                                                               AS `operator_id`,
       `f`.`idUser`                                                                 AS `beneficiary_id`,
       `f`.`idUser_auto`                                                                 AS `beneficiary_id_auto`,
       `f`.`value`                                                                  AS `value`,
       `f`.`Balance`                                                                AS `current_balance`,
       case
           when `f`.`idBalancesOperation` in (1, 6) then concat(lpad(`f`.`idBalancesOperation`, 3, '0'),
                                                                date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` = 13 then concat(lpad(16, 3, '0'), date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` = 16 then concat(lpad(`f`.`idBalancesOperation`, 3, '0'),
                                                           date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` = 18 then concat(lpad(`f`.`idBalancesOperation`, 3, '0'),
                                                           date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` = 38 then concat(lpad(`f`.`idBalancesOperation`, 3, '0'),
                                                           date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` in (42, 43) then concat(lpad(42, 3, '0'), date_format(
               str_to_date(substr(`f`.`ref`, 3, 6), '%y%m%d'), '%d%m%Y'))
           when `f`.`idBalancesOperation` = 51 then concat(lpad(`f`.`idBalancesOperation`, 3, '0'),
                                                           date_format(`f`.`Date`, '%d%m%Y'))
           when `f`.`idBalancesOperation` in (46, 47, 48, 49, 50, 44)
               then concat(lpad(44, 3, '0'), date_format(`f`.`Date`, '%d%m%Y')) end AS `reference`,
       `f`.`ref`                                                                    AS `ref`,
       case
           when `f`.`idBalancesOperation` = 18 then `f`.`id`
           when `f`.`ref` is null and `f`.`idBalancesOperation` in (1, 6) then `f`.`id`
           when `f`.`idBalancesOperation` = 13 then (select right(`u`.`ref`, 4)
from user_balances `u`
where `u`.`Date` = `f`.`Date`
  and `u`.`idUser` = `f`.`idUser`
  and `u`.`idBalancesOperation` = 16)
    when `f`.`ref` is null then NULL
    when `f`.`Description` = 'action5$' then (select right(`u`.`ref`, 4)
from user_balances `u`
where `u`.`Date` = `f`.`Date`
  and `u`.`idUser` = `f`.`idUser`
  and `u`.`idBalancesOperation` = 44
  and `u`.`value` > 0)
    when `f`.`idBalancesOperation` = 44 and `f`.`value` = 0 then (select right(`u`.`ref`, 4)
from user_balances `u`
where `u`.`ref` = `f`.`ref`
  and `u`.`idBalancesOperation` = 44
  and `u`.`value` > 0)
    else right(`f`.`ref`, 4) end                                             AS `code`,
           `f`.`WinPurchaseAmount`                                                      AS `payed`,
           `f`.`gifted_shares`                                                          AS `gifted_shares`,
           `f`.`PU`                                                                     AS `PU`,
           `f`.`Date`                                                                   AS `created_at`,
           `f`.`Date`                                                                   AS `updated_at`,
           `f`.`idamount`                                                               AS `idamount`
    from `filtred_userbalance` `f`;
CREATE TABLE IF NOT EXISTS trait_balances
SELECT * FROM rectified_userbalance;
delete
from trait_balances
where ref in ('442405231585', '442405231589', '442405231593', '442405231597', '442404291333', '422409112155');
OPEN user_balance_cursor;
read_loop:
    LOOP
        FETCH user_balance_cursor INTO v_code, v_reference, v_created_at, v_idamount,v_ref;
        IF done THEN
            LEAVE read_loop;
END IF;
        IF v_code IS NOT NULL THEN
            INSERT INTO rectified_balances
SELECT balance_operation_id,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       current_balance,
       CONCAT(v_reference, LPAD(new_code, 7, '0')) AS reference,
       ref,
       new_code,
       payed,
       gifted_shares,
       PU,
       case
           when balance_operation_id = 18 then DATE_SUB(created_at, INTERVAL 1 HOUR)
           else created_at end                        created_at,
       case
           when balance_operation_id = 18 then DATE_SUB(created_at, INTERVAL 1 HOUR)
           else created_at end                        updated_at,
       idamount
FROM trait_balances r
WHERE r.code * 1 = v_code * 1
  and (r.reference * 1 = v_reference * 1 or ref * 1 = v_ref * 1);
SET new_code = new_code + 1;
END IF;
END LOOP;
CLOSE user_balance_cursor;
insert into rectified_balances
select 39,
       description,
       Description_old,
       '11111111',
       beneficiary_id,
       beneficiary_id_auto,
       value / 2,
       current_balance,
       reference,
       ref,
       code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       5
from rectified_balances
where balance_operation_id = 38;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 205', current_timestamp());
insert into rectified_balances
select 54,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       gifted_shares,
       current_balance,
       reference,
       ref,
       code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       idamount
from rectified_balances
where balance_operation_id = 44
  and value > 0
  and gifted_shares < value
  and gifted_shares > 0;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 229', current_timestamp());

insert into rectified_balances
select 55,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       cast(gifted_shares / value as unsigned) * value,
       current_balance,
       reference,
       ref,
       code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       idamount
from rectified_balances
where balance_operation_id = 44
  and value > 0
  and gifted_shares >= value
  and gifted_shares > 0;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 254', current_timestamp());
insert into rectified_balances
select 54,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       gifted_shares - cast(gifted_shares / value as unsigned) * value,
       current_balance,
       reference,
       ref,
       code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       idamount
from rectified_balances
where balance_operation_id = 44
  and value > 0
  and gifted_shares >= value
  and gifted_shares > 0;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 278', current_timestamp());
insert into rectified_balances
select 52,
       description,
       Description_old,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       gifted_shares,
       current_balance,
       reference,
       ref,
       code,
       payed,
       gifted_shares,
       PU,
       created_at,
       updated_at,
       idamount
from rectified_balances
where balance_operation_id = 44
  and value = 0;
delete
from rectified_balances
where balance_operation_id = 44
  and value = 0;
update rectified_balances set value =gifted_shares where balance_operation_id = 53;
update rectified_balances
set description=concat(round(`value`, 2), '$ as welcome gift')
where balance_operation_id in (1, 6);
update rectified_balances
set description=concat(round(`value`, 2), '$ Transfered from my CB')
where balance_operation_id in (13);
update rectified_balances
set description=concat(round(`value`, 2), '$ Transfered to my BFS')
where balance_operation_id in (16);
update rectified_balances
set description=concat(round(`value`, 2), '$ transfered from system')
where balance_operation_id in (18);
update rectified_balances
set description=concat('perchase of ', round(`value`, 0) / 2, ' SMS')
where balance_operation_id in (38);
update rectified_balances
set description=concat('perchase of ', round(`value`, 0), ' SMS')
where balance_operation_id in (39);
update rectified_balances r
set description=concat(round(`value`, 2), ' Transfered to ', (select ifnull(ifnull(
                                                                                concat(enfirstname, ' ', enlastname),
                                                                                concat(arfirstname, ' ', arlastname)),
                                                                            fullphone_number)
                                                              from user_infos
                                                              where idUser * 1 = (select beneficiary_id
                                                                                  from rectified_balances
                                                                                  where balance_operation_id = 43
                                                                                    and reference = r.reference) *
                                                                                 1))
where balance_operation_id in (42);
update rectified_balances r
set description=concat(round(`value`, 2), ' Transfered from ', (select ifnull(ifnull(
                                                                                  concat(enfirstname, ' ', enlastname),
                                                                                  concat(arfirstname, ' ', arlastname)),
                                                                              fullphone_number)
                                                                from user_infos
                                                                where idUser * 1 = r.operator_id * 1))
where balance_operation_id in (43);
update rectified_balances r
set description=concat(round(`value`, 0), 'share(s) purchased',
                       case
                           when (select beneficiary_id
                                 from rectified_balances
                                 where balance_operation_id = 48
                                   and reference = r.reference) * 1 =
                                r.beneficiary_id * 1
                               then ''
                           else concat(' by ', (select ifnull(ifnull(concat(enfirstname, ' ', enlastname),
                                                                     concat(arfirstname, ' ', arlastname)),
                                                              fullphone_number)
                                                from user_infos
                                                where idUser * 1 = (select beneficiary_id
                                                                    from rectified_balances
                                                                    where balance_operation_id = 48
                                                                      and reference = r.reference) * 1)) end)
where balance_operation_id in (44, 46, 48, 54, 55);
update rectified_balances r
set description=concat('Sponsorship commission from ', (select ifnull(ifnull(concat(enfirstname, ' ', enlastname),
                                                                             concat(arfirstname, ' ', arlastname)),
                                                                      fullphone_number)
                                                        from user_infos
                                                        where idUser * 1 = (select beneficiary_id
                                                                            from rectified_balances
                                                                            where balance_operation_id = 44
                                                                              and reference = r.reference) * 1))
where balance_operation_id in (49, 50, 52);
update rectified_balances r
set description=concat('Compensation for the purchase of ',
                       (select round(`value`, 0)
                        from rectified_balances
                        where balance_operation_id = 44
                          and reference = r.reference), ' shares')
where balance_operation_id in (53);
truncate table cash_balances;
truncate table bfss_balances;
truncate table discount_balances;
truncate table shares_balances;
truncate table sms_balances;
truncate table tree_balances;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 384', current_timestamp());
insert into cash_balances(balance_operation_id,
                               description,
                               operator_id,
                               beneficiary_id,
                               beneficiary_id_auto,
                               value,
                               reference,
                               created_at,
                               updated_at)
select balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       reference,
       created_at,
       updated_at
from rectified_balances
where idamount = 1;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 405', current_timestamp());
insert into bfss_balances(percentage, balance_operation_id,
                               description,
                               operator_id,
                               beneficiary_id,
                               beneficiary_id_auto,
                               value,
                               reference,
                               created_at,
                               updated_at)
select case when balance_operation_id = 13 then 100 else 50 end,
       balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       reference,
       created_at,
       updated_at
from rectified_balances
where idamount = 2;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 427', current_timestamp());
insert into discount_balances(balance_operation_id,
                                   description,
                                   operator_id,
                                   beneficiary_id,
                                   beneficiary_id_auto,
                                   value,
                                   reference,
                                   created_at,
                                   updated_at)
select balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       reference,
       created_at,
       updated_at
from rectified_balances
where idamount = 3;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 448', current_timestamp());
insert into tree_balances(tree_id, balance_operation_id,
                               description,
                               operator_id,
                               beneficiary_id,
                               beneficiary_id_auto,
                               value,
                               reference,
                               created_at,
                               updated_at)
select 0,
       balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       reference,
       created_at,
       updated_at
from rectified_balances
where idamount = 4;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 470', current_timestamp());
insert into sms_balances(balance_operation_id,
                              description,
                              operator_id,
                              beneficiary_id,
                              beneficiary_id_auto,
                              value,
                              reference,
                              created_at,
                              updated_at)
select balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       reference,
       created_at,
       updated_at
from rectified_balances
where idamount = 5;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 491', current_timestamp());
insert into shares_balances(balance_operation_id,
                                 description,
                                 operator_id,
                                 beneficiary_id,
                                 beneficiary_id_auto,
                                 value,
                                 payed,
                            reference,
                            current_balance,
                                 created_at,
                                 updated_at)
select balance_operation_id,
       description,
       operator_id,
       beneficiary_id,
       beneficiary_id_auto,
       value,
       payed,
       reference,
       current_balance,
       created_at,
       updated_at
from rectified_balances
where idamount = 6;
update shares_balances s
set unit_price=(select value from cash_balances where balance_operation_id = 48 and reference = s.reference) /
                value
  , amount=(select value from cash_balances where balance_operation_id = 48 and reference = s.reference)
where balance_operation_id = 44;
update sms_balances s
set sms_price=(select value from bfss_balances where balance_operation_id = 38 and reference = s.reference) /
              value
  , amount=(select value from bfss_balances where balance_operation_id = 38 and reference = s.reference)
where balance_operation_id = 39;
update shares_balances s
set real_amount=case
                    when payed = 0 then 0
                    when payed = 1 then amount
                    when payed = 2 then total_amount end
where balance_operation_id = 44;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id,created_at
FROM cash_balances
ORDER BY beneficiary_id, created_at ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
truncate table user_current_balance_verticals;
truncate table user_current_balance_horisontals;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, CONCAT(' line number 549'), current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id, last_operation_value)
SELECT  idUser,id, 1,0,0,created_at,0,0
FROM users
where status >= 0;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 554', current_timestamp());

INSERT INTO user_current_balance_horisontals (user_id,user_id_auto)
SELECT idUser,id
FROM users
where status >= 0;

OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id,creation_date;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=1;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = last_balance + trans_value;
            ELSEIF io_value = 'O' THEN
                SET last_balance = last_balance - trans_value;
END IF;
            IF last_balance >= 0 THEN
UPDATE cash_balances
SET current_balance = last_balance
WHERE id = trans_id;
UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=1;
update user_current_balance_horisontals set cash_balance=last_balance
WHERE user_id*1 = trans_user_id*1;



END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE prc decimal(5, 2);
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id, percentage
FROM bfss_balances
ORDER BY beneficiary_id, created_at ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 620', current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT idUser,id, 2, 0, 0, created_at, 0, 0
FROM users
where status >= 0;


OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id,prc;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id * 1 = trans_user_id * 1
  and balance_id = 2;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE bfss_balances
SET current_balance = last_balance
WHERE id = trans_id;
UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id * 1 = trans_user_id * 1
  and balance_id = 2;

-- Vérifier si le 'prc' existe dans bfss_balance
IF JSON_CONTAINS(
                        (SELECT bfss_balance FROM user_current_balance_horisontals WHERE user_id * 1 = trans_user_id * 1),
                        JSON_OBJECT('type', prc),
                        '$'
                   ) THEN
                    -- Mettre à jour la valeur existante
UPDATE user_current_balance_horisontals
SET bfss_balance = JSON_SET(
    bfss_balance,
    CONCAT(
        '$[',
        JSON_UNQUOTE(
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    JSON_SEARCH(bfss_balance, 'one', prc, NULL, '$[*].type'),
                    '[', -1
                ),
                ']', 1
            )
        ),
        '].value'
    ),
    CASE
        WHEN io_value = 'I' THEN
            JSON_EXTRACT(bfss_balance, CONCAT(
                '$[',
                JSON_UNQUOTE(
                    SUBSTRING_INDEX(
                        SUBSTRING_INDEX(
                            JSON_SEARCH(bfss_balance, 'one', prc, NULL, '$[*].type'),
                            '[', -1
                        ),
                        ']', 1
                    )
                ),
                '].value'
                                       )) + trans_value
        WHEN io_value = 'O' THEN
            JSON_EXTRACT(bfss_balance, CONCAT(
                '$[',
                JSON_UNQUOTE(
                    SUBSTRING_INDEX(
                        SUBSTRING_INDEX(
                            JSON_SEARCH(bfss_balance, 'one', prc, NULL, '$[*].type'),
                            '[', -1
                        ),
                        ']', 1
                    )
                ),
                '].value'
                                       )) - trans_value
        END
                   )
WHERE user_id * 1 = trans_user_id * 1;

ELSE
                    -- Ajouter un nouveau prc
UPDATE user_current_balance_horisontals
SET bfss_balance = JSON_ARRAY_APPEND(
    bfss_balance,
    '$',
    JSON_OBJECT('type', prc, 'value',
                CASE
                    WHEN io_value = 'I' THEN trans_value
                    WHEN io_value = 'O' THEN -trans_value
                    END)
                   )
WHERE user_id * 1 = trans_user_id * 1;
END IF;


END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id
FROM discount_balances
ORDER BY beneficiary_id, created_at ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 755', current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT  idUser,id, 3,0,0,created_at,0,0
FROM users
where status >= 0;
OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=3;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE discount_balances
SET current_balance = last_balance
WHERE id = trans_id;
UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=3;
update user_current_balance_horisontals set discount_balance=last_balance
WHERE user_id*1 = trans_user_id*1;
END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id
FROM shares_balances
ORDER BY beneficiary_id, created_at, balance_operation_id ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 817', current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT  idUser, id,6,0,0,created_at,0,0
FROM users
where status >= 0;
OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=6;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE shares_balances
SET current_balance = last_balance
WHERE id = trans_id;
UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=6;
update user_current_balance_horisontals set share_balance=last_balance
WHERE user_id*1 = trans_user_id*1;
END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id
FROM sms_balances
ORDER BY beneficiary_id, created_at ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 879', current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT  idUser,id, 5,0,0,created_at,0,0
FROM users
where status >= 0;
OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=5;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE sms_balances
SET current_balance = last_balance
WHERE id = trans_id;

UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=5;
update user_current_balance_horisontals set sms_balance=last_balance
WHERE user_id*1 = trans_user_id*1;
END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id
FROM tree_balances
ORDER BY beneficiary_id, created_at ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 942', current_timestamp());

INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT  idUser, id,4,0,0,created_at,0,0
FROM users
where status >= 0;
OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=4;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE tree_balances
SET current_balance = last_balance
WHERE id = trans_id;

UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=4;
update user_current_balance_horisontals set tree_balance=last_balance
WHERE user_id*1 = trans_user_id*1;
end if;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE creation_date timestamp;
        DECLARE pool int;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, value, beneficiary_id,pool_id
FROM chance_balances
ORDER BY beneficiary_id, created_at, balance_operation_id ASC;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
INSERT INTO user_current_balance_verticals (user_id,user_id_auto, balance_id, current_balance, previous_balance, last_operation_date, last_operation_id,
                                   last_operation_value)
SELECT  idUser, id,7,0,0,created_at,0,0
FROM users
where status >= 0;
OPEN cur;
read_loop:
        LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id,pool;
            IF done THEN
                LEAVE read_loop;
END IF;
SELECT current_balance
INTO last_balance
FROM user_current_balance_verticals
WHERE user_id*1 = trans_user_id*1
  and balance_id=7;
SELECT IO
INTO io_value
FROM balance_operations
WHERE id = op_id;
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;
            IF last_balance >= 0 THEN
UPDATE chance_balances
SET current_balance = last_balance
WHERE id = trans_id;
UPDATE user_current_balance_verticals
SET previous_balance=current_balance,current_balance = last_balance,last_operation_date=creation_date,
    last_operation_id=op_id,last_operation_value=case
                                                     when io_value = 'I' then trans_value
                                                     when io_value = 'O' then -trans_value
    end
WHERE user_id*1 = trans_user_id*1
  and balance_id=7;
IF JSON_CONTAINS(
                        (SELECT chances_balance FROM user_current_balance_horisontals WHERE user_id * 1 = trans_user_id * 1),
                        JSON_OBJECT('type', pool),
                        '$'
                   ) THEN
                    -- Mettre à jour la valeur existante
UPDATE user_current_balance_horisontals
SET chances_balance = JSON_SET(
    chances_balance,
    CONCAT(
        '$[',
        JSON_UNQUOTE(
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    JSON_SEARCH(chances_balance, 'one', pool, NULL, '$[*].type'),
                    '[', -1
                ),
                ']', 1
            )
        ),
        '].value'
    ),
    CASE
        WHEN io_value = 'I' THEN
            JSON_EXTRACT(chances_balance, CONCAT(
                '$[',
                JSON_UNQUOTE(
                    SUBSTRING_INDEX(
                        SUBSTRING_INDEX(
                            JSON_SEARCH(chances_balance, 'one', pool, NULL, '$[*].type'),
                            '[', -1
                        ),
                        ']', 1
                    )
                ),
                '].value'
                                          )) + trans_value
        WHEN io_value = 'O' THEN
            JSON_EXTRACT(chances_balance, CONCAT(
                '$[',
                JSON_UNQUOTE(
                    SUBSTRING_INDEX(
                        SUBSTRING_INDEX(
                            JSON_SEARCH(chances_balance, 'one', pool, NULL, '$[*].type'),
                            '[', -1
                        ),
                        ']', 1
                    )
                ),
                '].value'
                                          )) - trans_value
        END
                      )
WHERE user_id * 1 = trans_user_id * 1;

ELSE
                    -- Ajouter un nouveau prc
UPDATE user_current_balance_horisontals
SET chances_balance = JSON_ARRAY_APPEND(
    chances_balance,
    '$',
    JSON_OBJECT('type', pool, 'value',
                CASE
                    WHEN io_value = 'I' THEN trans_value
                    WHEN io_value = 'O' THEN -trans_value
                    END)
                      )
WHERE user_id * 1 = trans_user_id * 1;
END IF;
END IF;
END LOOP;
CLOSE cur;

END;
BEGIN
        DECLARE done INT DEFAULT FALSE;
        DECLARE trans_id INT;
        DECLARE op_id INT;
        DECLARE trans_value DOUBLE;
        DECLARE trans_balance DOUBLE;
        DECLARE trans_user_id VARCHAR(9);
        DECLARE io_value CHAR(2);
        DECLARE last_balance DOUBLE;
        DECLARE cur CURSOR FOR
SELECT id, balance_operation_id, amount, beneficiary_id
FROM shares_balances
ORDER BY beneficiary_id, created_at,balance_operation_id ASC;

-- Gestion de la fin du curseur
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

        -- Temporaire pour stocker le dernier solde par utilisateur
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_user_balance (
                                                                   beneficiary_id VARCHAR(9) PRIMARY KEY,
                                                                   current_balance DOUBLE DEFAULT 0
        );

        -- Initialisation des soldes utilisateurs
INSERT INTO `debug_log` (`id`, `message`, `created_at`) VALUES (NULL, ' line number 1142', current_timestamp());

INSERT INTO temp_user_balance (beneficiary_id, current_balance)
SELECT DISTINCT beneficiary_id, 0
FROM shares_balances;

-- Parcourir les transactions
OPEN cur;

read_loop: LOOP
            FETCH cur INTO trans_id, op_id, trans_value, trans_user_id;
            IF done THEN
                LEAVE read_loop;
END IF;

            -- Récupérer le dernier solde de l'utilisateur
SELECT current_balance INTO last_balance
FROM temp_user_balance
WHERE beneficiary_id = trans_user_id;

-- Récupérer la valeur de IO (entrée ou sortie) depuis la table operations
SELECT IO INTO io_value
FROM balance_operations
WHERE id = op_id;

-- Calculer le nouveau solde provisoire
IF io_value = 'I' THEN
                SET last_balance = ROUND(last_balance + trans_value, 3);
            ELSEIF io_value = 'O' THEN
                SET last_balance = ROUND(last_balance - trans_value, 3);
END IF;

            -- Vérifier si le nouveau solde est négatif
            IF last_balance >= 0 THEN
                -- Mise à jour du solde de la transaction
UPDATE shares_balances
SET total_amount = last_balance
WHERE id = trans_id;

-- Mise à jour du dernier solde de l'utilisateur
UPDATE temp_user_balance
SET current_balance = last_balance
WHERE beneficiary_id = trans_user_id;
END IF;
END LOOP;

CLOSE cur;

-- Supprimer la table temporaire
DROP TEMPORARY TABLE temp_user_balance;
END;
drop table rectified_balances;
drop table trait_balances;
DROP VIEW IF EXISTS filtred_userbalance;
DROP VIEW IF EXISTS rectified_userbalance;
DROP VIEW IF EXISTS user_infos;
END
