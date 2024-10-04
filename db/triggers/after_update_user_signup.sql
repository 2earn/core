CREATE TRIGGER `after_update_user_signup` AFTER UPDATE ON `users`
    FOR EACH ROW if (new.status=0) THEN
UPDATE
    dev_learn.`users`
SET
    `status` = 'active',
    `updated_at` = now()
WHERE
    mobile=SUBSTRING(new.fullphone_number, 3);
end if
