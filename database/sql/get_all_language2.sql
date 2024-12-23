SELECT *
FROM user_balances ub
         inner join metta_users mu on ub.idUser = mu.idUser
