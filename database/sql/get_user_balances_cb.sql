SELECT ub.idUser,
       ub.id,
       ub.idSource,
       ub.Ref,
       ub.Date,
       bo.operation,
       ub.Description,
       case
           when ub.idSource = '11111111' then 'system'
           else
               (select concat(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, ''))
                from metta_users mu
                where mu.idUser = ub.idSource)
           end as source,
       case
           when bo.IO = 'I' then concat('+ ', ub.value)
           when bo.IO = 'O' then concat('- ', ub.value)
           when bo.IO = 'IO' then 'IO'
           end as value , ub.Balance as balance
FROM user_balances ub inner join balance_operations bo
on
    ub.idBalancesOperation = bo.id
where (bo.amounts_id = ? and ub.idUser = ?)
order by Date
