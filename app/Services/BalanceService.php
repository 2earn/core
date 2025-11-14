<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    const CURRENCY = '$';
    const SEPACE = ' ';


    public function getUserBalancesQuery($balance)
    {
        return DB::table($balance . ' as ub')
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->selectRaw('
        RANK() OVER (ORDER BY ub.created_at ASC, ub.reference ASC) as ranks,
        ub.beneficiary_id,
        ub.id,
        ub.operator_id,
        ub.reference,
        ub.created_at,
        bo.operation,
        ub.description,
        ub.current_balance,
        ub.balance_operation_id,
        CASE
            WHEN ub.operator_id = "11111111" THEN "system"
            ELSE (SELECT CONCAT(IFNULL(enfirstname, ""), " ", IFNULL(enlastname, ""))
                  FROM metta_users mu
                  WHERE mu.idUser = ub.operator_id)
        END as source,
        CASE
            WHEN bo.direction = "IN" THEN CONCAT("+", "$", FORMAT(ub.value, 3))
            WHEN bo.direction = "OUT" THEN CONCAT("-", "$", FORMAT(ub.value, 3))
        END as value
    ')
            ->where('ub.beneficiary_id', auth()->user()->idUser)
            ->orderBy('ub.created_at', 'desc')
            ->orderBy('ub.reference', 'desc');
    }

    /**
     * Get balance table name based on type
     */
    public function getBalanceTableName($typeAmounts)
    {
        return match ($typeAmounts) {
            'Balance-For-Shopping' => 'bfss_balances',
            'Discounts-Balance' => 'discount_balances',
            'SMS-Balance' => 'sms_balances',
            default => 'cash_balances',
        };
    }

    /**
     * Get user balances datatables response
     */
    public function getUserBalancesDatatables($typeAmounts)
    {
        $balance = $this->getBalanceTableName($typeAmounts);

        return datatables($this->getUserBalancesQuery($balance))
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->addColumn('formatted_date', function ($user) {
                return Carbon::parse($user->created_at)->format('Y-m-d');
            })
            ->editColumn('current_balance', function ($balance) {
                return self::CURRENCY . self::SEPACE . formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->rawColumns(['formatted_date'])
            ->make(true);
    }
}

