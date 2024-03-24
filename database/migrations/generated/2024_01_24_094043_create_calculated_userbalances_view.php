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
        DB::statement("CREATE VIEW `calculated_userbalances` AS select `b`.`idUser` AS `idUser`,`b`.`idamounts` AS `idamounts`,`a`.`solde` AS `solde` from (`2earn`.`usercurrentbalances` `b` left join (select `u`.`idUser` AS `idUser`,`u`.`idamount` AS `idamount`,ifnull(sum((`u`.`value` * (case when (`b`.`IO` = 'I') then 1 else -(1) end))),0) AS `solde` from (`2earn`.`user_balances` `u` join `2earn`.`balanceoperations` `b`) where ((`u`.`idBalancesOperation` = `b`.`idBalanceOperations`) and (year(`u`.`Date`) = year(sysdate())) and (`b`.`MODIFY_AMOUNT` = '1')) group by `u`.`idUser`,`u`.`idamount`) `a` on(((`b`.`idamounts` = `a`.`idamount`) and (`b`.`idUser` = `a`.`idUser`)))) order by `b`.`idUser`,`b`.`idamounts`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `calculated_userbalances`");
    }
};
