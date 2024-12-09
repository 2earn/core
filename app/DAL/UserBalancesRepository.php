<?php

namespace App\DAL;

use App\Models\BFSsBalances;
use App\Models\ChanceBalances;
use App\Models\TreeBalances;
use App\Models\UserCurrentBalancehorisontal;
use App\Services\Balances\BalancesFacade;
use Core\Enum\BalanceOperationsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\user_balance;
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
        $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $idUser)->first();
        if (!is_null($userCurrentBalancehorisontal)) {
            $calculetedUserBalances->soldeCB = formatSolde($userCurrentBalancehorisontal->cash_balance, $decimals);
            $calculetedUserBalances->soldeBFS = formatSolde(BFSsBalances::getTotal($userCurrentBalancehorisontal->bfss_balance), $decimals);
            $calculetedUserBalances->soldeDB = formatSolde($userCurrentBalancehorisontal->discount_balance, $decimals);
            $calculetedUserBalances->soldeT = formatSolde($userCurrentBalancehorisontal->tree_balance, $decimals);
            $calculetedUserBalances->soldeSMS = formatSolde($userCurrentBalancehorisontal->sms_balance, $decimals);
            $calculetedUserBalances->soldeChance = formatSolde(ChanceBalances::getTotal($userCurrentBalancehorisontal->chances_balance), $decimals);
            $calculetedUserBalances->soldeTree = formatSolde(TreeBalances::getTreesNumber($userCurrentBalancehorisontal->tree_balance), $decimals);
        }
        return $calculetedUserBalances;
    }

    public function getCurrentBalance($idUser)
    {
        return $this->getBalance($idUser, 2);
    }

    public function inserUserBalancestGetId($ref, BalanceOperationsEnum $operation, $date, $idSource, $iduserupline, $amount, $value)
    {
        // Converted
        // To be deleted
        $user_balance = new user_balance();
        $user_balance->ref = $ref;
        $user_balance->idBalancesOperation = $operation;
        $user_balance->Date = $date;
        $user_balance->idSource = $idSource;
        $user_balance->idUser = $iduserupline;
        $user_balance->idamount = $amount;
        $user_balance->value = $value;
        $user_balance->WinPurchaseAmount = "0.000";

        return $user_balance->save();
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
