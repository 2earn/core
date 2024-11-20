select id,
       name,
       lastName,
       fullphone_number,
       case
           when
               fullphone_number in (select users.fullphone_number from users where idUpline != ? and idUpline <> 0)
           then 'UNAVAILABLE Registred'
           when
           fullphone_number in (select users.fullphone_number from users where idUpline = ?)
           then 'DONE registred'
           when
           fullphone_number in (select users.fullphone_number from users where  idUpline = 0)
           then 'Available registred'
           when
           fullphone_number in (select users_invitations.fullNumber from users_invitations where NOW() <= users_invitations.dateFIn and users_invitations.idUser <> ?)
           then concat('Invited by ','user : ', (select users.name from users_invitations inner join users on users.idUser = users_invitations.idUser  where users_invitations.fullNumber=user_contacts.fullphone_number  ),'  dispo after  ',(select concat( Datediff( users_invitations.datefin, now()),' jours') from users_invitations where users_invitations.fullNumber=user_contacts.fullphone_number ))
           when
           fullphone_number in (select users_invitations.fullNumber from users_invitations where NOW() <= users_invitations.dateFIn and users_invitations.idUser = ?)
           then concat('Invited by ','you  : ', (select users.name from users_invitations inner join users on users.idUser = users_invitations.idUser  where users_invitations.fullNumber=user_contacts.fullphone_number  ),'  dispo after  ',(select concat( Datediff( users_invitations.datefin, now()),' jours') from users_invitations where users_invitations.fullNumber=user_contacts.fullphone_number ))
           else
           'Available'
end
as status
    from user_contacts
    where idUser = ?
