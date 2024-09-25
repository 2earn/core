<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW soldes_view AS
        SELECT
    `users`.`id` AS `id`,
    (SELECT
            IFNULL(SUM(`u`.`value` * CASE
                            WHEN `b`.`IO` = 'I' THEN 1
                            ELSE - 1
                        END),
                        0) AS `solde`
        FROM
            (`user_balances` `u`
            JOIN `balanceoperations` `b`)
        WHERE
            `u`.`idBalancesOperation` = `b`.`idBalanceOperations`
                AND `b`.`MODIFY_AMOUNT` = '1'
                AND `users`.`idUser` = `u`.`idUser`
                AND `u`.`idamount` = 1
        GROUP BY `u`.`idUser` , `u`.`idamount`) AS `cash`,
    (SELECT
            IFNULL(SUM(`u`.`value` * CASE
                            WHEN `b`.`IO` = 'I' THEN 1
                            ELSE - 1
                        END),
                        0) AS `solde`
        FROM
            (`user_balances` `u`
            JOIN `balanceoperations` `b`)
        WHERE
            `u`.`idBalancesOperation` = `b`.`idBalanceOperations`
                AND `b`.`MODIFY_AMOUNT` = '1'
                AND `users`.`idUser` = `u`.`idUser`
                AND `u`.`idamount` = 2
        GROUP BY `u`.`idUser` , `u`.`idamount`) AS `bfs`,
    (SELECT
            IFNULL(SUM(`u`.`value` * CASE
                            WHEN `b`.`IO` = 'I' THEN 1
                            ELSE - 1
                        END),
                        0) AS `solde`
        FROM
            (`user_balances` `u`
            JOIN `balanceoperations` `b`)
        WHERE
            `u`.`idBalancesOperation` = `b`.`idBalanceOperations`
                AND `b`.`MODIFY_AMOUNT` = '1'
                AND `users`.`idUser` = `u`.`idUser`
                AND `u`.`idamount` = 3
        GROUP BY `u`.`idUser` , `u`.`idamount`) AS `db`,
    (SELECT
            IFNULL(SUM(`u`.`value` * CASE
                            WHEN `b`.`IO` = 'I' THEN 1
                            ELSE - 1
                        END),
                        0) AS `solde`
        FROM
            (`user_balances` `u`
            JOIN `balanceoperations` `b`)
        WHERE
            `u`.`idBalancesOperation` = `b`.`idBalanceOperations`
                AND `b`.`MODIFY_AMOUNT` = '1'
                AND `users`.`idUser` = `u`.`idUser`
                AND `u`.`idamount` = 4
        GROUP BY `u`.`idUser` , `u`.`idamount`) AS `t`,
    (SELECT
            IFNULL(SUM(`u`.`value` * CASE
                            WHEN `b`.`IO` = 'I' THEN 1
                            ELSE - 1
                        END),
                        0) AS `solde`
        FROM
            (`user_balances` `u`
            JOIN `balanceoperations` `b`)
        WHERE
            `u`.`idBalancesOperation` = `b`.`idBalanceOperations`
                AND `b`.`MODIFY_AMOUNT` = '1'
                AND `users`.`idUser` = `u`.`idUser`
                AND `u`.`idamount` = 5
        GROUP BY `u`.`idUser` , `u`.`idamount`) AS `sms`,
    (SELECT
            SUM(`user_balances`.`value` + `user_balances`.`gifted_shares`) AS `sum(value+user_balances.gifted_shares)`
        FROM
            `user_balances`
        WHERE
            `users`.`idUser` = `user_balances`.`idUser`
        GROUP BY `user_balances`.`idUser`) AS `action`
FROM
    `users` AS `users`

    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS soldes_view;');
    }
};
