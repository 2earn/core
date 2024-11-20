SELECT u1.id                                      id,
       u1.idUser                                  idUser,
       u1.status                                  status,
       concat(mu.enFirstName, ' ', mu.enLastName) enName,
       mu.nationalID                              nationalID,
       u1.fullphone_number,
       u1.internationalID,
       u1.expiryDate,
       ir.created_at                              DateCreation,
       ir.id                                      irid,
       u2.name                                    Validator,
       ir.response,
       ir.responseDate                            DateReponce,
       ir.note
from identificationuserrequest ir
         inner join users u1 on ir.IdUser = u1.idUser
         inner join metta_users mu on ir.idUser = mu.idUser
         left join users u2 on ir.idUserResponse = u2.idUser
where (ir.status = ? or ir.status = ? or ir.status = ?)
