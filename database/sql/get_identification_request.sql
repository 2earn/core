SELECT u1.id id,
       u1.name USER,
    u1.fullphone_number,
    ir.created_at DateCreation,
    u2.name Validator,
    ir.response,
    ir.responseDate DateReponce,
    ir.note
FROM
    identificationuserrequest ir
    INNER JOIN users u1
ON
    ir.IdUser = u1.idUser
    LEFT JOIN users u2 ON
    ir.idUserResponse = u2.idUser
where ir.status = ? or ir.status = ?
