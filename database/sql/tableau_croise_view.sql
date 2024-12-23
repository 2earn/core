select `distinct_values`.`name`      AS `name`,
       `distinct_values`.`apha2`     AS `apha2`,
       `distinct_values`.`continant` AS `continant`,
       `distinct_values`.`id`        AS `id`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'CASH BALANCE'
                   then `distinct_values`.`value`
               else 0 end)           AS `CASH_BALANCE`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'BFS' then `distinct_values`.`value`
               else 0 end)           AS `BFS`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'DISCOUNT BALANCE'
                   then `distinct_values`.`value`
               else 0 end)           AS `DISCOUNT_BALANCE`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'SMS BALANCE' then `distinct_values`.`value`
               else 0 end)           AS `SMS_BALANCE`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'SOLD SHARES' then `distinct_values`.`value`
               else 0 end)           AS `SOLD_SHARES`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'GIFTED SHARES'
                   then `distinct_values`.`value`
               else 0 end)           AS `GIFTED_SHARES`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'TOTAL SHARES'
                   then `distinct_values`.`value`
               else 0 end)           AS `TOTAL_SHARES`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'SHARES REVENUE'
                   then `distinct_values`.`value`
               else 0 end)           AS `SHARES_REVENUE`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'TRANSFERT MADE'
                   then `distinct_values`.`value`
               else 0 end)           AS `TRANSFERT_MADE`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'COUNT USERS' then `distinct_values`.`value`
               else 0 end)           AS `COUNT_USERS`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'COUNT TRAIDERS'
                   then `distinct_values`.`value`
               else 0 end)           AS `COUNT_TRAIDERS`,
       sum(case
               when cast(`distinct_values`.`lib` as char charset utf8mb4) = 'COUNT REAL TRAIDERS'
                   then `distinct_values`.`value`
               else 0 end)           AS `COUNT_REAL_TRAIDERS`
from (select `allbycountries`.`name`      AS `name`,
             `allbycountries`.`apha2`     AS `apha2`,
             `allbycountries`.`continant` AS `continant`,
             `allbycountries`.`lib`       AS `lib`,
             `allbycountries`.`value`     AS `value`,
             `allbycountries`.`id`        AS `id`
      from `allbycountries`) `distinct_values`
group by `distinct_values`.`name`, `distinct_values`.`apha2`, `distinct_values`.`continant`, `distinct_values`.`id`
