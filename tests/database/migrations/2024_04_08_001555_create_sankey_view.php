<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSankeyView extends Migration
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
            CREATE VIEW `sankey` AS select case when `u`.`idSource` = '11111111' then '2earn.cash' else `u`.`idSource` end AS `from`,ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) AS `to`,sum(`u`.`value`) AS `weight` from ((`user_balances` `u` join `users` `s` on(`u`.`idUser` = `s`.`idUser`)) join `metta_users` `meta` on(`u`.`idUser` = `meta`.`idUser`)) where `u`.`idBalancesOperation` = 18 and `u`.`value` > 0 group by case when `u`.`idSource` = '11111111' then '2earn.cash' else `u`.`idSource` end,ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) union select ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) AS `from`,`c1`.`name` AS `to`,sum(`u`.`value`) AS `weight` from ((((((`user_balances` `u` join `users` `s` on(`u`.`idSource` = `s`.`idUser`)) join `countries` `c` on(`s`.`idCountry` = `c`.`id`)) join `metta_users` `meta` on(`u`.`idSource` = `meta`.`idUser`)) join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) where `u`.`idBalancesOperation` = 43 and `s`.`is_representative` = 1 group by `c1`.`name`,ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) union select ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) AS `from`,ifnull(concat(ifnull(`meta1`.`arFirstName`,`meta1`.`enFirstName`),' ',ifnull(`meta1`.`arLastName`,`meta1`.`enLastName`)),`s1`.`fullphone_number`) AS `to`,sum(`u`.`value`) AS `weight` from ((((((`user_balances` `u` join `users` `s` on(`u`.`idSource` = `s`.`idUser`)) join `countries` `c` on(`s`.`idCountry` = `c`.`id`)) join `metta_users` `meta` on(`u`.`idSource` = `meta`.`idUser`)) join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) where `u`.`idBalancesOperation` = 43 and `u`.`idSource` <> '11111111' and `s1`.`is_representative` = 1 group by ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`),ifnull(concat(ifnull(`meta1`.`arFirstName`,`meta1`.`enFirstName`),' ',ifnull(`meta1`.`arLastName`,`meta1`.`enLastName`)),`s1`.`fullphone_number`) union select `c`.`name` AS `from`,NULL AS `to`,sum(`u`.`value`) AS `weight` from ((((((`user_balances` `u` join `users` `s` on(`u`.`idSource` = `s`.`idUser`)) join `countries` `c` on(`s`.`idCountry` = `c`.`id`)) join `metta_users` `meta` on(`u`.`idSource` = `meta`.`idUser`)) join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) where `u`.`idBalancesOperation` = 43 and `u`.`idSource` <> '11111111' and `s1`.`is_representative` = 1 group by `c`.`name`,ifnull(concat(ifnull(`meta1`.`arFirstName`,`meta1`.`enFirstName`),' ',ifnull(`meta1`.`arLastName`,`meta1`.`enLastName`)),`s1`.`fullphone_number`) union select `c1`.`name` AS `from`,'Sold Shares' AS `to`,sum(`u`.`value`) AS `weight` from ((((((`user_balances` `u` join `users` `s` on(`u`.`idSource` = `s`.`idUser`)) join `countries` `c` on(`s`.`idCountry` = `c`.`id`)) join `metta_users` `meta` on(`u`.`idSource` = `meta`.`idUser`)) join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) where `u`.`idBalancesOperation` = 48 and `s1`.`is_representative` <> 1 group by `c1`.`name` union select ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) AS `from`,'Sold Shares' AS `to`,sum(`u`.`value`) AS `weight` from ((((((`user_balances` `u` join `users` `s` on(`u`.`idSource` = `s`.`idUser`)) join `countries` `c` on(`s`.`idCountry` = `c`.`id`)) join `metta_users` `meta` on(`u`.`idSource` = `meta`.`idUser`)) join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) where `u`.`idBalancesOperation` = 48 and `s1`.`is_representative` = 1 group by ifnull(concat(ifnull(`meta`.`arFirstName`,`meta`.`enFirstName`),' ',ifnull(`meta`.`arLastName`,`meta`.`enLastName`)),`s`.`fullphone_number`) union select ifnull(concat(ifnull(`meta1`.`arFirstName`,`meta1`.`enFirstName`),' ',ifnull(`meta1`.`arLastName`,`meta1`.`enLastName`)),`s1`.`fullphone_number`) AS `from`,'Representative Cash Balance' AS `to`,sum(case when `b`.`IO` = 'I' then `u`.`value` else -`u`.`value` end) AS `weight` from ((((`user_balances` `u` join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) join `balanceoperations` `b` on(`u`.`idBalancesOperation` = `b`.`idBalanceOperations`)) where `u`.`idamount` = 1 and `s1`.`is_representative` = 1 group by ifnull(concat(ifnull(`meta1`.`arFirstName`,`meta1`.`enFirstName`),' ',ifnull(`meta1`.`arLastName`,`meta1`.`enLastName`)),`s1`.`fullphone_number`) union select `c1`.`name` AS `from`,'Users Cash Balance' AS `to`,sum(case when `b`.`IO` = 'I' then `u`.`value` else -`u`.`value` end) AS `weight` from ((((`user_balances` `u` join `users` `s1` on(`u`.`idUser` = `s1`.`idUser`)) join `metta_users` `meta1` on(`u`.`idUser` = `meta1`.`idUser`)) join `countries` `c1` on(`s1`.`idCountry` = `c1`.`id`)) join `balanceoperations` `b` on(`u`.`idBalancesOperation` = `b`.`idBalanceOperations`)) where `u`.`idamount` = 1 and `s1`.`is_representative` <> 1 group by `c1`.`name`
        SQL;
    }

    private function dropView()
    {
        return <<<SQL
            DROP VIEW IF EXISTS `sankey`;
        SQL;
    }
}
