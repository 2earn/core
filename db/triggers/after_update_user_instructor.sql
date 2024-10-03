CREATE TRIGGER `after_update_user_instructor` AFTER UPDATE ON `users`
    FOR EACH ROW BEGIN
    DECLARE iduser int;
    SELECT id into iduser FROM dev_learn.users
    where mobile=SUBSTRING(new.fullphone_number, 3);

    if new.instructor =1 THEN
INSERT INTO dev_learn.`become_instructors`(

    `user_id`,
    `role`,
    `package_id`,

    `status`,
    `created_at`
)
VALUES(

    iduser,
    'teacher',
    2,

    'pending',
    now()
);
end if;
END
