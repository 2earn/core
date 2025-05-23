SELECT recharge_requests.Date,
       USER.name USER,
    recharge_requests.userPhone userphone,
    recharge_requests.amount
FROM
    recharge_requests
    LEFT JOIN users USER
ON
    USER.idUser = recharge_requests.idUser
