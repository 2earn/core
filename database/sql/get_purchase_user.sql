SELECT list1.DateAchat                                                       AS DateAchat,
       IFNULL(list1.ReferenceAchat, '')                                      AS ReferenceAchat,
       list1.idUser                                                          AS idUser,
       list1.item_title                                                      AS item_title,
       list1.nbr_achat                                                       AS nbrAchat,
       list1.Amout                                                           AS Amout,
       list1.invitationPurshase                                              AS invitationPurshase,
       list1.visit                                                           AS visit,
       list1.PRC_BFS                                                         AS PRC_BFS,
       list1.PRC_CB                                                          AS PRC_CB,
       list1.CashBack_BFS                                                    AS CashBack_BFS,
       list1.CashBack_CB                                                     AS CashBack_CB,
       list1.PRC_BFS + list1.PRC_CB + list1.CashBack_BFS + list1.CashBack_CB AS Economy
FROM (SELECT ub.idUser                                   AS idUser,
             ub.item_title                               AS item_title,
             (SELECT ub2.ref
              FROM user_balances ub2
              WHERE ub2.idBalancesOperation = 17
                AND ub2.idUser = ub.idUser
                AND ub.id_item = ub2.id_item)            AS ReferenceAchat,
             (SELECT ub2.Date
              FROM user_balances ub2
              WHERE ub2.idBalancesOperation = 17
                AND ub2.idUser = ub.idUser
                AND ub.id_item = ub2.id_item)            AS DateAchat,
             la.nbr_achat                                AS nbr_achat,
             (SELECT SUM(IFNULL(ub2.value, 0))
              FROM user_balances ub2
              WHERE ub2.idBalancesOperation = 17
                AND ub2.idUser = ub.idUser
                AND ub.id_item = ub2.id_item)            AS Amout,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 10
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS invitationPurshase,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 8
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS visit,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 24
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS PRC_BFS,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 26
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS PRC_CB,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 23
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS CashBack_BFS,
             IFNULL((SELECT SUM(IFNULL(ub2.value, 0))
                     FROM user_balances ub2
                     WHERE ub2.idBalancesOperation = 25
                       AND ub2.idUser = ub.idUser
                       AND ub.id_item = ub2.id_item), 0) AS CashBack_CB
      FROM ((user_balances ub
          JOIN balance_operations bo ON (ub.idBalancesOperation = bo.id))
          JOIN (SELECT `user_balances`.`idUser`       AS `idUser`,
                       `user_balances`.`id_item`      AS `id_item`,
                       `user_balances`.`item_title`   AS `item_title`,
                       `user_balances`.`id_plateform` AS `id_plateform`,
                       COUNT(0)                       AS `nbr_achat`
                FROM `user_balances`
                WHERE `user_balances`.`idBalancesOperation` = 17
                GROUP BY `user_balances`.`idUser`, `user_balances`.`id_item`, `user_balances`.`item_title`,
                         `user_balances`.`id_plateform`) la ON (la.id_item = ub.id_item
          AND ub.idUser = la.idUser))
      GROUP BY ub.idUser, ub.item_title, ub.id_item,
               (SELECT ub2.Date
                FROM user_balances ub2
                WHERE ub2.idBalancesOperation = 17
                  AND ub2.idUser = ub.idUser
                  AND ub.id_item = ub2.id_item),
               (SELECT ub2.ref
                FROM user_balances ub2
                WHERE ub2.idBalancesOperation = 17
                  AND ub2.idUser = ub.idUser
                  AND ub.id_item = ub2.id_item), la.nbr_achat) list1
where idUser = ?
