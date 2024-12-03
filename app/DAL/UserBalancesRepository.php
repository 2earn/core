<?php

namespace App\DAL;

use App\Models\UserCurrentBalancehorisontal;
use Core\Enum\BalanceOperationsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\calculated_userbalances;
use Core\Models\user_balance;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;

class  UserBalancesRepository implements IUserBalancesRepository
{
    const SOLD_INIT = 0;

    public function getBalance($idUser, $decimals = 2)
    {
        $calculetedUserBalances = UserCurrentBalancehorisontal::where('user_id', $idUser)->first();

        if (!is_null($calculetedUserBalances)) {
            $calculetedUserBalances->soldeCB = formatSolde($calculetedUserBalances->cash_balance, $decimals);
            $calculetedUserBalances->soldeBFS = formatSolde($calculetedUserBalances->bfss_balance, $decimals);
            $calculetedUserBalances->soldeDB = formatSolde($calculetedUserBalances->discount_balance, $decimals);
            $calculetedUserBalances->soldeT = formatSolde($calculetedUserBalances->tree_balance, $decimals);
            $calculetedUserBalances->soldeSMS = formatSolde($calculetedUserBalances->sms_balance, $decimals);

        } else {
            $calculetedUserBalances->soldeCB = self::SOLD_INIT;
            $calculetedUserBalances->soldeBFS = self::SOLD_INIT;
            $calculetedUserBalances->soldeDB = self::SOLD_INIT;
            $calculetedUserBalances->soldeT = self::SOLD_INIT;
            $calculetedUserBalances->soldeSMS = self::SOLD_INIT;
        }

        return $calculetedUserBalances;
    }

    public function getCurrentBalance($idUser)
    {
        $calculetedUserBalances = new  calculated_userbalances;
        $solde = DB::table('usercurrentbalances')
            ->where("idUser", "=", $idUser)
            ->select('dernier_value', 'idamounts')
            ->get();
        if ($solde->isNotEmpty()) {
            $calculetedUserBalances->soldeCB = $solde->where("idamounts", "=", "1")->first()->dernier_value;
            $calculetedUserBalances->soldeBFS = $solde->where("idamounts", "=", "2")->first()->dernier_value;
            $calculetedUserBalances->soldeDB = $solde->where("idamounts", "=", "3")->first()->dernier_value;
            $calculetedUserBalances->soldeT = $solde->where("idamounts", "4")->first()->dernier_value;
        } else {
            $calculetedUserBalances->soldeCB = self::SOLD_INIT;
            $calculetedUserBalances->soldeBFS = self::SOLD_INIT;
            $calculetedUserBalances->soldeDB = self::SOLD_INIT;
            $calculetedUserBalances->soldeT = self::SOLD_INIT;
        }
        return $calculetedUserBalances;
    }

    public function inserUserBalancestGetId($ref, BalanceOperationsEnum $operation, $date, $idSource, $iduserupline, $amount, $value)
    {
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
        $soldeAmount = 0;
        $solde = DB::select(getSqlFromPath('get_solde_by_amount'), [$idUser, $idamount]);

        $solde = collect($solde);
        if ($solde->isNotEmpty()) {
            $soldeAmount = $solde->where("idamounts", "=", $idamount)->first()->solde;
        }
        return $soldeAmount;
    }
}
