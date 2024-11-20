select CAST(a.x AS DECIMAL(10, 0)) as x, case when a.me = 1 then a.y else null end as y
from (SELECT id,
             CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL (10, 0))       AS x,
             CAST((value + gifted_shares) * PU / value AS DECIMAL(10, 2)) AS y,
             case
                 when id in (select id
                             from user_balances
                             where idBalancesOperation = 44
                               AND idUser = ?) then 1
                 else 0 end                                               as me
      FROM user_balances
      WHERE idBalancesOperation = 44
        and value > 0

      ORDER BY
          Date) as a
union all
select CAST(b.x - b.value AS DECIMAL(10, 0)) as x, case when b.me = 1 then b.y else null end as y
from (SELECT id,
             value,
             SUM(value)        OVER (ORDER BY id)  AS x, CAST((value + gifted_shares) * PU / value AS DECIMAL(10, 2)) AS y,
             case
                 when id in (select id
                             from user_balances
                             where idBalancesOperation = 44
                               AND idUser = ?) then 1
                 else 0 end as me
      FROM user_balances
      WHERE idBalancesOperation = 44

      ORDER BY
          Date) as b
ORDER BY x
