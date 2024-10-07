<?php

namespace App\DAL;

use Core\Enum\BalanceOperationsEnum;
use Core\Interfaces\IUserBalancesRepository;
use Core\Models\calculated_userbalances;
use Core\Models\user_balance;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;

class  UserBalancesRepository implements IUserBalancesRepository
{
    const SOLD_INIT = 0;

    public function getBalance($idUser, $decimals = 2): calculated_userbalances
    {
        $calculetedUserBalances = new  calculated_userbalances;
        $solde = DB::select("  select * from (
 SELECT
        b.idUser AS idUser,
        b.idamounts AS idamounts,
        a.solde AS solde
    FROM
        (usercurrentbalances b
        LEFT JOIN (SELECT
            u.idUser AS idUser,
                u.idamount AS idamount,
                IFNULL(SUM(u.value / u.PrixUnitaire * CASE
                    WHEN b.IO = 'I' THEN 1
                    ELSE - 1
                END), 0) AS `solde`
        FROM
            (user_balances u
        JOIN balanceoperations b)
        WHERE
            u.idBalancesOperation = b.idBalanceOperations

                AND b.MODIFY_AMOUNT = '1'
        GROUP BY u.idUser , u.idamount) a ON (b.idamounts = a.idamount
            AND b.idUser = a.idUser))
    ORDER BY b.idUser , b.idamounts) as liste  where  liste.idUser = ?", [$idUser]);

        $solde = collect($solde);

        if ($solde->isNotEmpty()) {
            $calculetedUserBalances->soldeCB = $solde->where("idamounts", "=", "1")->first()->solde;

            $calculetedUserBalances->soldeBFS = $solde->where("idamounts", "=", "2")->first()->solde;
            $calculetedUserBalances->soldeDB = $solde->where("idamounts", "=", "3")->first()->solde;
            $calculetedUserBalances->soldeT = $solde->where("idamounts", "4")->first()->solde;
            $calculetedUserBalances->soldeSMS = $solde->where("idamounts", "5")->first()->solde;

            $calculetedUserBalances->soldeCB = formatSolde(round($calculetedUserBalances->soldeCB), $decimals);
            $calculetedUserBalances->soldeBFS = formatSolde($calculetedUserBalances->soldeBFS, $decimals);
            $calculetedUserBalances->soldeDB = formatSolde($calculetedUserBalances->soldeDB, $decimals);
            $calculetedUserBalances->soldeT = formatSolde($calculetedUserBalances->soldeT, $decimals);
            $calculetedUserBalances->soldeSMS = formatSolde($calculetedUserBalances->soldeSMS, $decimals);
        } else {
            $calculetedUserBalances->soldeCB = self::SOLD_INIT;
            $calculetedUserBalances->soldeBFS = self::SOLD_INIT;
            $calculetedUserBalances->soldeDB = self::SOLD_INIT;
            $calculetedUserBalances->soldeT = self::SOLD_INIT;
            $calculetedUserBalances->soldeSMS = self::SOLD_INIT;
        }

        return $calculetedUserBalances;
    }

    public function getCurrentBalance($idUser): calculated_userbalances
    {
        $calculetedUserBalances = new  calculated_userbalances;
        $solde = DB::table('usercurrentbalances')
            ->where("idUser", "=", $idUser)
            ->select('dernier_value', 'idamounts')->get();
        if ($solde->isNotEmpty()) {
            $calculetedUserBalances->soldeCB = $solde
                ->where("idamounts", "=", "1")
                ->first()->dernier_value;
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
        $solde = DB::select("  select * from (
 SELECT
        b.idUser AS idUser,
        b.idamounts AS idamounts,
        a.solde AS solde
    FROM
        (usercurrentbalances b
        LEFT JOIN (SELECT
            u.idUser AS idUser,
                u.idamount AS idamount,
                IFNULL(SUM(u.value / u.PrixUnitaire * CASE
                    WHEN b.IO = 'I' THEN 1
                    ELSE - 1
                END), 0) AS `solde`
        FROM
            (user_balances u
        JOIN balanceoperations b)
        WHERE
            u.idBalancesOperation = b.idBalanceOperations
                AND YEAR(u.Date) = YEAR(SYSDATE())
                AND b.MODIFY_AMOUNT = '1'
        GROUP BY u.idUser , u.idamount) a ON (b.idamounts = a.idamount
            AND b.idUser = a.idUser))
    ORDER BY b.idUser , b.idamounts) as liste  where  liste.idUser = ? and liste.idamounts = ? ", [$idUser, $idamount]);

        $solde = collect($solde);
        if ($solde->isNotEmpty()) {
            $soldeAmount = $solde->where("idamounts", "=", $idamount)->first()->solde;
        }
        return $soldeAmount;
    }
}
