CREATE TRIGGER `after_update_user_signup`
    AFTER UPDATE
    ON `users`
    FOR EACH ROW if(new.status=0) THEN UPDATE database_learn.`users` SET `status` = 'active', `updated_at` = UNIX_TIMESTAMP(), password=new.password WHERE mobile=SUBSTRING(new.fullphone_number, 3);
end if
