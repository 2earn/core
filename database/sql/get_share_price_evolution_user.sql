select CAST(a.x AS DECIMAL(10, 0)) as x, case when a.me = 1 then a.y else null end as y
from (SELECT id,
             CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL (10, 0))       AS x,
             CAST(unit_price AS DECIMAL(10, 2)) AS y,
             case
                 when id in (select id
                             from shares_balances
                             where balance_operation_id = 44
                               AND beneficiary_id = ?) then 1
                 else 0 end                                               as me
      FROM shares_balances
      WHERE balance_operation_id = 44


      ORDER BY
          created_at) as a

union all

select CAST(b.x - b.value AS DECIMAL(10, 0)) as x, case when b.me = 1 then b.y else null end as y
from (SELECT id,
             value,
             SUM(value)        OVER (ORDER BY id)  AS x,
          CAST(unit_price AS DECIMAL(10, 2)) AS y,
             case
                 when id in (select id
                             from shares_balances
                             where balance_operation_id = 44
                               AND beneficiary_id = ?) then 1
                 else 0 end as me
      FROM shares_balances


      ORDER BY
          created_at) as b
ORDER BY x
