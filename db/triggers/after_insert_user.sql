CREATE TRIGGER `after_insert_user` AFTER INSERT ON `users`
    FOR EACH ROW INSERT INTO dev_learn.`users`(
    `full_name`,
    `role_name`,
    `role_id`,
    `mobile`,
    `status`,
    `created_at`,
    `updated_at`
)
                 VALUES(
                           SUBSTRING(new.fullphone_number, 3),
                           'user',
                           '1',
                           SUBSTRING(new.fullphone_number, 3),
                           'pending',
                           now(),
                           now()
                       )
