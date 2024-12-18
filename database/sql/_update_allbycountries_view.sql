select `u`.`balance_id`                                     AS `idamount`,
       case
           when `u`.`balance_id` = 1 then 'CASH BALANCE'
           when `u`.`balance_id` = 2 then 'BFS'
           when `u`.`balance_id` = 3 then 'DISCOUNT BALANCE'
           when `u`.`balance_id` = 5 then 'SMS BALANCE' end AS `lib`,
       `c`.`name`                                           AS `name`,
       `c`.`apha2`                                          AS `apha2`,
       `c`.`continant`                                      AS `continant`,
       `c`.`id`                                             AS `id`,
       sum(`u`.`current_balance`)                           AS `value`
from ((`dev_2earn`.`user_current_balance_verticals` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`user_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_id` not in (4, 6, 7)
  and `s`.`is_representative` <> 1
group by `c`.`continant`, `u`.`balance_id`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 4                AS `4`,
       'SOLD SHARES'    AS `lib`,
       `c`.`name`       AS `name`,
       `c`.`apha2`      AS `apha2`,
       `c`.`continant`  AS `continant`,
       `c`.`id`         AS `id`,
       sum(`u`.`value`) AS `value`
from ((`dev_2earn`.`shares_balances` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`beneficiary_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_operation_id` = 44
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 6                AS `6`,
       'GIFTED SHARES'  AS `lib`,
       `c`.`name`       AS `name`,
       `c`.`apha2`      AS `apha2`,
       `c`.`continant`  AS `continant`,
       `c`.`id`         AS `id`,
       sum(`u`.`value`) AS `value`
from ((`dev_2earn`.`shares_balances` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`beneficiary_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_operation_id` in (52, 53, 54, 55)
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 7                          AS `idamount`,
       'TOTAL SHARES'             AS `lib`,
       `c`.`name`                 AS `name`,
       `c`.`apha2`                AS `apha2`,
       `c`.`continant`            AS `continant`,
       `c`.`id`                   AS `id`,
       sum(`u`.`current_balance`) AS `value`
from ((`dev_2earn`.`user_current_balance_verticals` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`user_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_id` = 6
group by `c`.`continant`, `u`.`balance_id`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 8                AS `8`,
       'SHARES REVENUE' AS `lib`,
       `c`.`name`       AS `name`,
       `c`.`apha2`      AS `apha2`,
       `c`.`continant`  AS `continant`,
       `c`.`id`         AS `id`,
       sum(`u`.`value`) AS `value`
from ((`dev_2earn`.`cash_balances` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`beneficiary_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_operation_id` = 48
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 9                      AS `9`,
       'TRANSFERT MADE'       AS `lib`,
       `c`.`name`             AS `name`,
       `c`.`apha2`            AS `apha2`,
       `c`.`continant`        AS `continant`,
       `c`.`id`               AS `id`,
       sum(`u`.`real_amount`) AS `value`
from ((`dev_2earn`.`shares_balances` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`beneficiary_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_operation_id` = 44
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 10                  AS `10`,
       'COUNT USERS'       AS `COUNT USERS`,
       `c`.`name`          AS `name`,
       `c`.`apha2`         AS `apha2`,
       `c`.`continant`     AS `continant`,
       `c`.`id`            AS `id`,
       count(`s`.`idUser`) AS `value`
from (`dev_2earn`.`users` `s` join `dev_2earn`.`countries` `c`)
where `s`.`idCountry` = `c`.`id`
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 11               AS `idamount`,
       'COUNT TRAIDERS' AS `lib`,
       `c`.`name`       AS `name`,
       `c`.`apha2`      AS `apha2`,
       `c`.`continant`  AS `continant`,
       `c`.`id`         AS `id`,
       count(`u`.`id`)  AS `value`
from ((`dev_2earn`.`user_current_balance_verticals` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`user_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_id` = 6
  and `u`.`current_balance` > 0
group by `c`.`continant`, `u`.`balance_id`, `c`.`name`, `c`.`apha2`, `c`.`id`
union
select 12                                   AS `12`,
       'COUNT REAL TRAIDERS'                AS `lib`,
       `c`.`name`                           AS `name`,
       `c`.`apha2`                          AS `apha2`,
       `c`.`continant`                      AS `continant`,
       `c`.`id`                             AS `id`,
       count(distinct `u`.`beneficiary_id`) AS `value`
from ((`dev_2earn`.`shares_balances` `u` join `dev_2earn`.`users` `s`) join `dev_2earn`.`countries` `c`)
where `u`.`beneficiary_id` = `s`.`idUser`
  and `s`.`idCountry` = `c`.`id`
  and `u`.`balance_operation_id` = 44
  and `u`.`real_amount` > 0
group by `c`.`continant`, `c`.`name`, `c`.`apha2`, `c`.`id`
