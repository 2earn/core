<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableauCroiseView extends Migration
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
            CREATE VIEW `tableau_croise` AS select `distinct_values`.`name` AS `name`,`distinct_values`.`apha2` AS `apha2`,`distinct_values`.`continant` AS `continant`,sum(case when `distinct_values`.`lib` = 'CASH BALANCE' then `distinct_values`.`value` else 0 end) AS `CASH_BALANCE`,sum(case when `distinct_values`.`lib` = 'BFS' then `distinct_values`.`value` else 0 end) AS `BFS`,sum(case when `distinct_values`.`lib` = 'DISCOUNT BALANCE' then `distinct_values`.`value` else 0 end) AS `DISCOUNT_BALANCE`,sum(case when `distinct_values`.`lib` = 'SMS BALANCE' then `distinct_values`.`value` else 0 end) AS `SMS_BALANCE`,sum(case when `distinct_values`.`lib` = 'SOLD SHARES' then `distinct_values`.`value` else 0 end) AS `SOLD_SHARES`,sum(case when `distinct_values`.`lib` = 'GIFTED SHARES' then `distinct_values`.`value` else 0 end) AS `GIFTED_SHARES`,sum(case when `distinct_values`.`lib` = 'TOTAL SHARES' then `distinct_values`.`value` else 0 end) AS `TOTAL_SHARES`,sum(case when `distinct_values`.`lib` = 'SHARES REVENUE' then `distinct_values`.`value` else 0 end) AS `SHARES_REVENUE`,sum(case when `distinct_values`.`lib` = 'TRANSFERT MADE' then `distinct_values`.`value` else 0 end) AS `TRANSFERT_MADE`,sum(case when `distinct_values`.`lib` = 'COUNT USERS' then `distinct_values`.`value` else 0 end) AS `COUNT_USERS`,sum(case when `distinct_values`.`lib` = 'COUNT TRAIDERS' then `distinct_values`.`value` else 0 end) AS `COUNT_TRAIDERS`,sum(case when `distinct_values`.`lib` = 'COUNT REAL TRAIDERS' then `distinct_values`.`value` else 0 end) AS `COUNT_REAL_TRAIDERS` from (select `allbycountries`.`name` AS `name`,`allbycountries`.`apha2` AS `apha2`,`allbycountries`.`continant` AS `continant`,`allbycountries`.`lib` AS `lib`,`allbycountries`.`value` AS `value` from `allbycountries`) `distinct_values` group by `distinct_values`.`name`,`distinct_values`.`apha2`,`distinct_values`.`continant`
        SQL;
    }

    private function dropView()
    {
        return <<<SQL
            DROP VIEW IF EXISTS `tableau_croise`;
        SQL;
    }
}
