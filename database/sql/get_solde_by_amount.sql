select *
from (SELECT b.idUser    AS idUser,
             b.idamounts AS idamounts,
             a.solde     AS solde
      FROM (usercurrentbalances b
          LEFT JOIN (SELECT u.idUser     AS idUser,
                            u.idamount   AS idamount,
                            IFNULL(SUM(u.value / u.PrixUnitaire * CASE
                                                                      WHEN b.IO = 'I' THEN 1
                                                                      ELSE - 1
                                END), 0) AS `solde`
                     FROM (user_balances u
                         JOIN balance_operations b)
                     WHERE u.idBalancesOperation = b.id
                               AND YEAR (u.Date) = YEAR(SYSDATE())
                AND b.modify_amount = '1'
        GROUP BY u.idUser , u.idamount) a ON (b.idamounts = a.idamount
            AND b.idUser = a.idUser))
ORDER BY b.idUser, b.idamounts) as liste
where liste.idUser = ? and liste.idamounts = ?
