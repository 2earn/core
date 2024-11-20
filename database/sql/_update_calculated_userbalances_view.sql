CREATE OR REPLACE VIEW  calculated_userbalances AS
SELECT `b`.`idUser` AS `idUser`, `b`.`idamounts` AS `idamounts`, `a`.`solde` AS `solde`
FROM (`database_name`.`usercurrentbalances` `b` LEFT JOIN (SELECT `u`.`idUser`   AS `idUser`,
                                                                  `u`.`idamount` AS `idamount`,
                                                                  IFNULL(
                                                                      SUM(`u`.`value` * CASE WHEN `b`.`IO` = 'I' THEN 1 ELSE - 1 END),
                                                                      0)         AS `solde`
                                                           FROM (`database_name`.`user_balances` `u` JOIN `database_name`.`balance_operations` `b`)
                                                           WHERE `u`.`idBalancesOperation` = `b`.`id`
                                                             AND `b`.`modify_amount` = '1'
                                                           GROUP BY `u`.`idUser`, `u`.`idamount`) `a`
      ON (`b`.`idamounts` = `a`.`idamount` AND `b`.`idUser` = `a`.`idUser`))
UNION
SELECT `database_name`.`user_balances`.`idUser`                                                       AS `idUser`,
       6                                                                                              AS `6`,
       SUM(`database_name`.`user_balances`.`value` +
           `database_name`.`user_balances`.`gifted_shares`)                                           AS `sum(value+user_balances.gifted_shares)`
FROM `database_name`.`user_balances`
GROUP BY `database_name`.`user_balances`.`idUser`
ORDER BY `idUser`, `idamounts`;
