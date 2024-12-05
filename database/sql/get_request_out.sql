SELECT recharge_requests.Date,
       USER.name USER,
    recharge_requests.payeePhone userphone,
    recharge_requests.amount
FROM
    recharge_requests
    LEFT JOIN users USER
ON
    USER.idUser = recharge_requests.idPayee
WHERE
    recharge_requests.idSender =?
