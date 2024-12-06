<?php

namespace App\DAL;

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
        $calculetedUserBalances = UserCurrentBalancehorisontal::where('user_id', $idUser)->first();
        if (!is_null($calculetedUserBalances)) {
            $calculetedUserBalances->soldeCB = formatSolde($calculetedUserBalances->cash_balance, $decimals);
            $calculetedUserBalances->soldeBFS = formatSolde($calculetedUserBalances->bfss_balance, $decimals);
            $calculetedUserBalances->soldeDB = formatSolde($calculetedUserBalances->discount_balance, $decimals);
            $calculetedUserBalances->soldeT = formatSolde($calculetedUserBalances->tree_balance, $decimals);
            $calculetedUserBalances->soldeSMS = formatSolde($calculetedUserBalances->sms_balance, $decimals);
            $calculetedUserBalances->soldeChance = formatSolde($calculetedUserBalances->chances_balance, $decimals);
            $calculetedUserBalances->soldeTree = formatSolde($calculetedUserBalances->tree_balance, $decimals);
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
