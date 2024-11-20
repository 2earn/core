SELECT u1.id id, u1.name User ,u1.fullphone_number, ir.created_at DateCreation, u2.name Validator, ir.response, ir.responseDate DateReponce , ir.note
from identificationuserrequest ir
    inner join users u1
on ir.IdUser = u1.idUser
    left join users u2 on ir.idUserResponse = u2.idUser
where ir.status = ? or ir.status = ?
