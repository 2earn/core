select history.ref                  reference,
       IFNULL(usend.name, "System") send,
       users.name                   receiver,
       ifnull(title, "") action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce
from history
    inner join users
on history.id_reciver = users.idUser
    left join users usend on history.id_send = users.idUser
    left join action_history on history.id_action = action_history.id
