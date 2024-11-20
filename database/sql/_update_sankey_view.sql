CREATE OR REPLACE VIEW sankey AS
SELECT CASE WHEN `u`.`idSource` = '11111111' THEN '2earn.cash' ELSE `u`.`idSource` END          AS `from`,
       IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `to`,
       SUM(`u`.`value`)                                                                         AS `weight`
FROM ((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
       ON (`u`.`idUser` = `s`.`idUser`)) JOIN `database_name`.`metta_users` `meta` ON (`u`.`idUser` = `meta`.`idUser`))
WHERE `u`.`idBalancesOperation` = 18
  AND `u`.`value` > 0
GROUP BY CASE WHEN `u`.`idSource` = '11111111' THEN '2earn.cash' ELSE `u`.`idSource` END,
         IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
UNION
SELECT IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `from`,
       `c1`.`name`                                                                              AS `to`,
       SUM(`u`.`value`)                                                                         AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 43
  AND `s`.`is_representative` = 1
GROUP BY `c1`.`name`,
         IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
UNION
SELECT IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)    AS `from`,
       IFNULL(CONCAT(IFNULL(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                     IFNULL(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`) AS `to`,
       SUM(`u`.`value`)                                                                            AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 43
  AND `u`.`idSource` <> '11111111'
  AND `s1`.`is_representative` = 1
GROUP BY IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`),
         IFNULL(CONCAT(IFNULL(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       IFNULL(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
UNION
SELECT `c`.`name` AS `from`, NULL AS `to`, SUM(`u`.`value`) AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 43
  AND `u`.`idSource` <> '11111111'
  AND `s1`.`is_representative` = 1
GROUP BY `c`.`name`,
         IFNULL(CONCAT(IFNULL(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       IFNULL(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
UNION
SELECT `c1`.`name` AS `from`, 'Sold Shares' AS `to`, SUM(`u`.`value`) AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 48
  AND `s1`.`is_representative` <> 1
GROUP BY `c1`.`name`
UNION
SELECT IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `from`,
       'Sold Shares'                                                                            AS `to`,
       SUM(`u`.`value`)                                                                         AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 48
  AND `s1`.`is_representative` = 1
GROUP BY IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
UNION
SELECT IFNULL(CONCAT(IFNULL(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                     IFNULL(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`) AS `from`,
       'Representative Cash Balance'                                                               AS `to`,
       SUM(CASE WHEN `b`.`io` = 'I' THEN `u`.`value` ELSE - `u`.`value` END)                       AS `weight`
FROM ((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s1`
         ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
        ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1`
       ON (`s1`.`idCountry` = `c1`.`id`)) JOIN `database_name`.`balance_operations` `b`
      ON (`u`.`idBalancesOperation` = `b`.`id`))
WHERE `u`.`idamount` = 1
  AND `s1`.`is_representative` = 1
GROUP BY IFNULL(CONCAT(IFNULL(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       IFNULL(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
UNION
SELECT `c1`.`name`                                                           AS `from`,
       'Users Cash Balance'                                                  AS `to`,
       SUM(CASE WHEN `b`.`io` = 'I' THEN `u`.`value` ELSE - `u`.`value` END) AS `weight`
FROM ((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s1`
         ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
        ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1`
       ON (`s1`.`idCountry` = `c1`.`id`)) JOIN `database_name`.`balance_operations` `b`
      ON (`u`.`idBalancesOperation` = `b`.`id`))
WHERE `u`.`idamount` = 1
  AND `s1`.`is_representative` <> 1
GROUP BY `c1`.`name`
UNION
SELECT 'PayTabs Transfert' AS `from`, `c1`.`name` AS `to`, SUM(`u`.`value`) AS `weight`
FROM ((((((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
           ON (`u`.`idSource` = `s`.`idUser`)) JOIN `database_name`.`countries` `c`
          ON (`s`.`idCountry` = `c`.`id`)) JOIN `database_name`.`metta_users` `meta`
         ON (`u`.`idSource` = `meta`.`idUser`)) JOIN `database_name`.`users` `s1`
        ON (`u`.`idUser` = `s1`.`idUser`)) JOIN `database_name`.`metta_users` `meta1`
       ON (`u`.`idUser` = `meta1`.`idUser`)) JOIN `database_name`.`countries` `c1` ON (`s1`.`idCountry` = `c1`.`id`))
WHERE `u`.`idBalancesOperation` = 51
  AND `s`.`is_representative` <> 1
GROUP BY `c1`.`name`
UNION
SELECT 'PayTabs Transfert'                                                                      AS `from`,
       IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `to`,
       SUM(`u`.`value`)                                                                         AS `weight`
FROM ((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
       ON (`u`.`idUser` = `s`.`idUser`)) JOIN `database_name`.`metta_users` `meta` ON (`u`.`idUser` = `meta`.`idUser`))
WHERE `u`.`idBalancesOperation` = 51
  AND `s`.`is_representative` = 1
GROUP BY IFNULL(CONCAT(IFNULL(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       IFNULL(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
UNION
SELECT 'SPONSORSHIP COMMISSION' AS `from`, `c`.`name` AS `to`, SUM(`u`.`value`) AS `weight`
FROM ((`database_name`.`user_balances` `u` JOIN `database_name`.`users` `s`
       ON (`u`.`idUser` = `s`.`idUser`)) JOIN `database_name`.`countries` `c` ON (`s`.`idCountry` = `c`.`id`))
WHERE `u`.`idBalancesOperation` = 49
  AND `s`.`is_representative` <> 1
GROUP BY `c`.`name`;
