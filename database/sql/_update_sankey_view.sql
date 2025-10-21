CREATE OR REPLACE VIEW sankey AS
select case when `u`.`operator_id` = '11111111' then '2earn.cash' else `u`.`operator_id` end    AS `from`,
       ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `to`,
       sum(`u`.`value`)                                                                         AS `weight`
from ((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
       on (`u`.`beneficiary_id` = `s`.`idUser`)) join `database_name`.`metta_users` `meta`
      on (`u`.`beneficiary_id` = `meta`.`idUser`))
where `u`.`balance_operation_id` = 18
  and `u`.`value` > 0
group by case when `u`.`operator_id` = '11111111' then '2earn.cash' else `u`.`operator_id` end,
         ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
union
select ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `from`,
       `c1`.`name`                                                                              AS `to`,
       sum(`u`.`value`)                                                                         AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 43
  and `s`.`is_representative` = 1
group by `c1`.`name`,
         ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
union
select ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)    AS `from`,
       ifnull(concat(ifnull(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                     ifnull(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`) AS `to`,
       sum(`u`.`value`)                                                                            AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 43
  and `u`.`operator_id` <> '11111111'
  and `s1`.`is_representative` = 1
group by ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`),
         ifnull(concat(ifnull(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       ifnull(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
union
select `c`.`name` AS `from`, NULL AS `to`, sum(`u`.`value`) AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 43
  and `u`.`operator_id` <> '11111111'
  and `s1`.`is_representative` = 1
group by `c`.`name`,
         ifnull(concat(ifnull(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       ifnull(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
union
select `c1`.`name` AS `from`, 'Sold Shares' AS `to`, sum(`u`.`value`) AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 48
  and `s1`.`is_representative` <> 1
group by `c1`.`name`
union
select ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `from`,
       'Sold Shares'                                                                            AS `to`,
       sum(`u`.`value`)                                                                         AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 48
  and `s1`.`is_representative` = 1
group by ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
union
select ifnull(concat(ifnull(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                     ifnull(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`) AS `from`,
       'Representative Cash Balance'                                                               AS `to`,
       sum(case when `b`.`direction` = 'IN' then `u`.`value` else -`u`.`value` end)                        AS `weight`
from ((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s1`
         on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
        on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
       on (`s1`.`idCountry` = `c1`.`id`)) join `database_name`.`balance_operations` `b`
      on (`u`.`balance_operation_id` = `b`.`id`))
where `s1`.`is_representative` = 1
group by ifnull(concat(ifnull(`meta1`.`arFirstName`, `meta1`.`enFirstName`), ' ',
                       ifnull(`meta1`.`arLastName`, `meta1`.`enLastName`)), `s1`.`fullphone_number`)
union
select `c1`.`name`                                                          AS `from`,
       'Users Cash Balance'                                                 AS `to`,
       sum(case when `b`.`direction` = 'IN' then `u`.`value` else -`u`.`value` end) AS `weight`
from ((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s1`
         on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
        on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
       on (`s1`.`idCountry` = `c1`.`id`)) join `database_name`.`balance_operations` `b`
      on (`u`.`balance_operation_id` = `b`.`id`))
where `s1`.`is_representative` <> 1
group by `c1`.`name`
union
select 'PayTabs Transfert' AS `from`, `c1`.`name` AS `to`, sum(`u`.`value`) AS `weight`
from ((((((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
           on (`u`.`operator_id` = `s`.`idUser`)) join `database_name`.`countries` `c`
          on (`s`.`idCountry` = `c`.`id`)) join `database_name`.`metta_users` `meta`
         on (`u`.`operator_id` = `meta`.`idUser`)) join `database_name`.`users` `s1`
        on (`u`.`beneficiary_id` = `s1`.`idUser`)) join `database_name`.`metta_users` `meta1`
       on (`u`.`beneficiary_id` = `meta1`.`idUser`)) join `database_name`.`countries` `c1`
      on (`s1`.`idCountry` = `c1`.`id`))
where `u`.`balance_operation_id` = 51
  and `s`.`is_representative` <> 1
group by `c1`.`name`
union
select 'PayTabs Transfert'                                                                      AS `from`,
       ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                     ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`) AS `to`,
       sum(`u`.`value`)                                                                         AS `weight`
from ((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
       on (`u`.`beneficiary_id` = `s`.`idUser`)) join `database_name`.`metta_users` `meta`
      on (`u`.`beneficiary_id` = `meta`.`idUser`))
where `u`.`balance_operation_id` = 51
  and `s`.`is_representative` = 1
group by ifnull(concat(ifnull(`meta`.`arFirstName`, `meta`.`enFirstName`), ' ',
                       ifnull(`meta`.`arLastName`, `meta`.`enLastName`)), `s`.`fullphone_number`)
union
select 'SPONSORSHIP COMMISSION' AS `from`, `c`.`name` AS `to`, sum(`u`.`value`) AS `weight`
from ((`database_name`.`cash_balances` `u` join `database_name`.`users` `s`
       on (`u`.`beneficiary_id` = `s`.`idUser`)) join `database_name`.`countries` `c` on (`s`.`idCountry` = `c`.`id`))
where `u`.`balance_operation_id` = 49
  and `s`.`is_representative` <> 1
group by `c`.`name`
