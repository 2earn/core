CREATE TRIGGER `after_update_user_identification` AFTER UPDATE ON `users`
    FOR EACH ROW BEGIN
    DECLARE name VARCHAR(150);
    SELECT concat(enFirstName,' ', enLastName) into name FROM `metta_users` mu
    where mu.idUser=new.idUser ;

    if new.status in(2,4) THEN
    UPDATE
        dev_learn.`users`
    SET
        `full_name` = nvl(name,SUBSTRING(new.fullphone_number, 3)),
        email=new.email,
        `updated_at` = now()
    WHERE
        mobile=SUBSTRING(new.fullphone_number, 3);
end if;
END
