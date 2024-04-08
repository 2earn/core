<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllbycountriesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView()
    {
        return <<<SQL
            CREATE VIEW `allbycountries` AS select `u`.`idamount` AS `idamount`,case when `u`.`idamount` = 1 then 'CASH BALANCE' when `u`.`idamount` = 2 then 'BFS' when `u`.`idamount` = 3 then 'DISCOUNT BALANCE' when `u`.`idamount` = 5 then 'SMS BALANCE' end AS `lib`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(case when `b`.`IO` = 'I' then `u`.`value` else -`u`.`value` end) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` not in (4,6) and `s`.`is_representative` <> 1 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 4 AS `4`,'SOLD SHARES' AS `SOLD SHARES`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(case when `b`.`IO` = 'I' then `u`.`value` else -`u`.`value` end) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 6 AS `6`,'GIFTED SHARES' AS `GIFTED SHARES`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(`u`.`gifted_shares`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 7 AS `7`,'TOTAL SHARES' AS `TOTAL SHARES`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(`u`.`gifted_shares` + `u`.`value`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 8 AS `8`,'SHARES REVENUE' AS `SHARES REVENUE`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(`u`.`value`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 1 and `u`.`idBalancesOperation` = 48 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 9 AS `9`,'TRANSFERT MADE' AS `TRANSFERT MADE`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,sum(`u`.`Balance`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 10 AS `10`,'COUNT USERS' AS `COUNT USERS`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,count(`s`.`idUser`) AS `value` from (`users` `s` join `countries` `c`) where `s`.`idCountry` = `c`.`id` group by `c`.`continant`,`c`.`name`,`c`.`apha2` union select 11 AS `11`,'COUNT TRAIDERS' AS `COUNT TRAIDERS`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,count(distinct `u`.`idUser`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2` union select 12 AS `12`,'COUNT REAL TRAIDERS' AS `COUNT REAL TRAIDERS`,`c`.`name` AS `name`,`c`.`apha2` AS `apha2`,`c`.`continant` AS `continant`,count(distinct `u`.`idUser`) AS `value` from (((`user_balances` `u` join `users` `s`) join `countries` `c`) join `balanceoperations` `b`) where `u`.`idUser` = `s`.`idUser` and `s`.`idCountry` = `c`.`id` and `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and `u`.`idamount` = 6 and `u`.`idBalancesOperation` = 44 and `u`.`Balance` > 0 group by `c`.`continant`,`u`.`idamount`,`c`.`name`,`c`.`apha2`
        SQL;
    }

    private function dropView()
    {
        return <<<SQL
            DROP VIEW IF EXISTS `allbycountries`;
        SQL;
    }
}
