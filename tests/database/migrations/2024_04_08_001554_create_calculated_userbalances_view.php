<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculatedUserbalancesView extends Migration
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
            CREATE VIEW `calculated_userbalances` AS select `b`.`idUser` AS `idUser`,`b`.`idamounts` AS `idamounts`,`a`.`solde` AS `solde` from (`usercurrentbalances` `b` left join (select `u`.`idUser` AS `idUser`,`u`.`idamount` AS `idamount`,ifnull(sum(`u`.`value` * case when `b`.`IO` = 'I' then 1 else -1 end),0) AS `solde` from (`user_balances` `u` join `balanceoperations` `b`) where `u`.`idBalancesOperation` = `b`.`idBalanceOperations` and year(`u`.`Date`) = year(sysdate()) and `b`.`MODIFY_AMOUNT` = '1' group by `u`.`idUser`,`u`.`idamount`) `a` on(`b`.`idamounts` = `a`.`idamount` and `b`.`idUser` = `a`.`idUser`)) order by `b`.`idUser`,`b`.`idamounts`
        SQL;
    }

    private function dropView()
    {
        return <<<SQL
            DROP VIEW IF EXISTS `calculated_userbalances`;
        SQL;
    }
}
