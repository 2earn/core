select recharge_requests.Date, user.name user  , recharge_requests.payeePhone userphone, recharge_requests.amount
from recharge_requests left join users user
on user.idUser = recharge_requests.idPayee
where recharge_requests.idUser = ?
