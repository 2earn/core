CREATE VIEW soldes_view AS
SELECT `database_name`.`users`.`id`                        AS `id`,
       (SELECT IFNULL(SUM(`u`.`value` * CASE WHEN `b`.`IO` = 'I' THEN 1 ELSE - 1 END), 0) AS `solde`
        FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
        WHERE `u`.`idBalancesOperation` = `b`.`id`
          AND `b`.`modify_amount` = '1'
          AND `database_name`.`users`.`idUser` = `u`.`idUser`
          AND `u`.`idamount` = 1
        GROUP BY `u`.`idUser`, `u`.`idamount`)             AS `cash`,
       (SELECT IFNULL(SUM(`u`.`value` * CASE WHEN `b`.`io` = 'I' THEN 1 ELSE - 1 END), 0) AS `solde`
        FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
        WHERE `u`.`idBalancesOperation` = `b`.`id`
          AND `b`.`modify_amount` = '1'
          AND `database_name`.`users`.`idUser` = `u`.`idUser`
          AND `u`.`idamount` = 2
        GROUP BY `u`.`idUser`, `u`.`idamount`)             AS `bfs`,
       (SELECT IFNULL(SUM(`u`.`value` * CASE WHEN `b`.`io` = 'I' THEN 1 ELSE - 1 END), 0) AS `solde`
        FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
        WHERE `u`.`idBalancesOperation` = `b`.`id`
          AND `b`.`modify_amount` = '1'
          AND `database_name`.`users`.`idUser` = `u`.`idUser`
          AND `u`.`idamount` = 3
        GROUP BY `u`.`idUser`, `u`.`idamount`)             AS `db`,
       (SELECT IFNULL(SUM(`u`.`value` * CASE WHEN `b`.`io` = 'I' THEN 1 ELSE - 1 END), 0) AS `solde`
        FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
        WHERE `u`.`idBalancesOperation` = `b`.`id`
          AND `b`.`modify_amount` = '1'
          AND `database_name`.`users`.`idUser` = `u`.`idUser`
          AND `u`.`idamount` = 4
        GROUP BY `u`.`idUser`, `u`.`idamount`)             AS `t`,
       (SELECT IFNULL(SUM(`u`.`value` * CASE WHEN `b`.`io` = 'I' THEN 1 ELSE - 1 END), 0) AS `solde`
        FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
        WHERE `u`.`idBalancesOperation` = `b`.`id`
          AND `b`.`modify_amount` = '1'
          AND `database_name`.`users`.`idUser` = `u`.`idUser`
          AND `u`.`idamount` = 5
        GROUP BY `u`.`idUser`, `u`.`idamount`)             AS `sms`,
       (SELECT SUM(`database_name`.`user_balances`.`value` +
                   `database_name`.`user_balances`.`gifted_shares`) AS `sum(value+user_balances.gifted_shares)`
        FROM `database_name`.`user_balances`
        WHERE `database_name`.`users`.`idUser` = `database_name`.`user_balances`.`idUser`
        GROUP BY `database_name`.`user_balances`.`idUser`) AS `action`
FROM `database_name`.`users`;
