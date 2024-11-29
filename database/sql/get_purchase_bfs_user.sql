SELECT RANK()     OVER (
        ORDER BY ub.Date desc
    ) as ranks  , ub.idUser,
       ub.id,
       ub.idSource,
       ub.Ref,
       ub.Date,
       bo.Operation,
       ub.Description,
       case
           when ub.idSource = '11111111' then 'system'
           else
               (select concat(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, ''))
                from metta_users mu
                where mu.idUser = ub.idSource)
           end as source,
       case
           when bo.IO = 'I' then concat('+ ', '$ ', format(ub.value / PrixUnitaire, 3))
           when bo.IO = 'O' then concat('- ', format(ub.value / PrixUnitaire, 3), ' $')
           when bo.IO = 'IO' then 'IO'
           end as value , case when idAmount = 5  then  concat( format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,0) ,' ') when idAmount = 3 then concat('$ ', format(  SUM(case when bo.IO = 'I' then   format(format(ub.value,3)/format(PrixUnitaire,3) ,3)
when bo.IO ='O' then  format(format(ub.value,3)/format(PrixUnitaire *-1,3) ,3)
when bo.IO = 'IO' then 'IO'
end)   OVER(ORDER BY date) ,2) ) else concat( '$ ', format( ub.balance ,3,'en_EN') ) end  as balance,ub.PrixUnitaire, bo.IO as sensP
FROM user_balances ub inner join balance_operations bo
on
    ub.idBalancesOperation = bo.id
where (bo.amounts_id = ? and ub.idUser = ?)
order by Date
