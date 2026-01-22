<?php

namespace App\DAL;

use App\Models\TreeBalances;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use App\Interfaces\IUserBalancesRepository;
use phpDocumentor\Reflection\Types\Collection;

class  UserBalancesRepository implements IUserBalancesRepository
{
    const SOLD_INIT = 0;

    public function getBalance($idUser, $decimals = 2)
    {
        $calculetedUserBalances  = new \stdClass();
        $calculetedUserBalances->soldeCB = $calculetedUserBalances->soldeBFS =
        $calculetedUserBalances->soldeDB = $calculetedUserBalances->soldeT =
        $calculetedUserBalances->soldeSMS = $calculetedUserBalances->soldeChance =
        $calculetedUserBalances->soldeTree = self::SOLD_INIT;
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($idUser);
        if (!is_null($userCurrentBalancehorisontal)) {
            $calculetedUserBalances->soldeCB = formatSolde($userCurrentBalancehorisontal->cash_balance, $decimals);
            $calculetedUserBalances->soldeBFS = formatSolde(Balances::getTotalBfs($userCurrentBalancehorisontal), $decimals);
            $calculetedUserBalances->soldeDB = formatSolde($userCurrentBalancehorisontal->discount_balance, $decimals);
            $calculetedUserBalances->soldeT = formatSolde($userCurrentBalancehorisontal->tree_balance, $decimals);
            $calculetedUserBalances->soldeSMS = formatSolde($userCurrentBalancehorisontal->sms_balance, $decimals);
            $calculetedUserBalances->soldeChance =  formatSolde(Balances::getTotalChance($userCurrentBalancehorisontal), $decimals);
            $calculetedUserBalances->soldeTree = formatSolde($userCurrentBalancehorisontal->tree_balance, $decimals);
        }
        return $calculetedUserBalances;
    }

    public function getCurrentBalance($idUser)
    {
        return $this->getBalance($idUser, 2);
    }

    public function getSoldeByAmount($idUser, $idamount)
    {
        return match ($idamount) {
            1 =>  BalancesFacade::getCash($idUser),
            2 =>  BalancesFacade::getBfss($idUser),
            3 =>  BalancesFacade::getDiscount($idUser),
            4 =>  BalancesFacade::getTree($idUser),
            5 =>  BalancesFacade::getSms($idUser),
            default =>  BalancesFacade::getCash($idUser),
        };
    }
}
