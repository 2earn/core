select ref,
       u.idUser,
       u.idamount, Date, u.idBalancesOperation, b.operation, u.Description, case when b.IO ='I' then value else - value
end
value ,u.balance from user_balances u,balance_operations b,users s
where u.idBalancesOperation=b.id
  and u.idUser=s.idUser
and u.idamount not in(4,6)  and u.idUser=? and u.idamount=? order by Date
