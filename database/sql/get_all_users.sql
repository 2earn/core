SELECT RANK() OVER (
        ORDER BY u.id
    ) as N , u.idUser ,u.status ,ue.registred_from,u.fullphone_number ,  concat( ifnull(mu.enFirstName,''),' ', ifnull(mu.enLastName,'')) as LatinName ,
       concat( ifnull(mu.arFirstName,''),' ',ifnull(mu.arLastName,'')) as ArabicName ,
       (select max(ub.date) from  user_balances ub where ub.idUser = u.idUser ) as lastOperation ,
       (select  c.name from countries c where c.phonecode = ue.idCountry limit 1 ) as country
from users u
    left join user_earns ue on ue.idUser = u.IdUser
    left join metta_users mu on mu.idUser = u.idUser
