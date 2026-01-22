<?php

namespace App\Services\Balances;

use App\Enums\BalanceEnum;
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

    /**
     * Get Purchase BFS User datatables response
     */
    public function getPurchaseBFSUserDatatables($userId, $type)
    {
        $query = DB::table('bfss_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.created_at ASC) as ranks'),
                'ub.beneficiary_id', 'ub.id', 'ub.operator_id', 'ub.reference', 'ub.created_at', 'bo.operation', 'ub.description',
                DB::raw(" CASE WHEN ub.operator_id = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.beneficiary_id) END AS source "),
                DB::raw(" CASE WHEN bo.direction = 'IN' THEN CONCAT('+', '$', FORMAT(ub.value, 2)) WHEN bo.direction = 'OUT' THEN CONCAT('-', '$', FORMAT(ub.value , 2)) END AS value "),
                'bo.direction as sensP',
                'ub.percentage as percentage',
                'ub.current_balance',
                'ub.balance_operation_id'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->where('ub.beneficiary_id', $userId);

        if ($type != null && $type != 'ALL') {
            $query->where('percentage', $type);
        }

        return datatables($query->orderBy('created_at')->orderBy('percentage')->get())
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->editColumn('description', function ($row) {
                return Balances::generateDescriptionById($row->id, BalanceEnum::BFS->value);
            })
            ->editColumn('current_balance', function ($balance) {
                return self::CURRENCY . self::SEPACE . formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    /**
     * Get SMS balance transactions for a user
     */
    public function getSmsUserDatatables($userId)
    {
        $results = DB::table('sms_balances as u')
            ->select(
                DB::raw("RANK() OVER (ORDER BY u.created_at ASC, u.reference ASC) as ranks"),
                'u.id',
                'u.reference',
                'u.beneficiary_id',
                'u.created_at',
                'u.balance_operation_id',
                'b.operation',
                DB::raw("CASE WHEN b.direction = 'IN' THEN u.value ELSE -u.value END AS value"),
                'u.current_balance'
            )
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser')
            ->where('u.beneficiary_id', $userId)
            ->orderBy('u.created_at', 'DESC')
            ->get();

        return datatables($results)
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->make(true);
    }

    /**
     * Get Chance balance transactions for a user
     */
    public function getChanceUserDatatables($userId)
    {
        $userData = DB::table('chance_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.created_at DESC) as ranks'),
                'ub.beneficiary_id',
                'ub.id',
                'ub.operator_id',
                'ub.reference',
                'ub.created_at',
                'bo.operation',
                'ub.description',
                'ub.value',
                'ub.current_balance',
                'ub.balance_operation_id',
                DB::raw(" CASE WHEN ub.beneficiary_id = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.beneficiary_id) END AS source "),
                'bo.direction as sensP'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->where('ub.beneficiary_id', $userId)
            ->orderBy('created_at')
            ->get();

        return datatables($userData)
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->editColumn('value', function ($balance) {
                return formatSolde($balance->value, 2);
            })
            ->editColumn('description', function ($row) {
                return Balances::generateDescriptionById($row->id, BalanceEnum::CHANCE->value);
            })
            ->editColumn('current_balance', function ($balance) {
                return formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    /**
     * Get Shares balance transactions for a user
     */
    public function getSharesSoldeDatatables($userId)
    {
        $query = DB::table('shares_balances')
            ->where('beneficiary_id', $userId)
            ->orderBy('id', 'desc');

        return datatables($query)
            ->addColumn('total_price', function ($sharesBalances) {
                return number_format($sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('share_price', function ($sharesBalances) {
                if ($sharesBalances->value != 0)
                    return $sharesBalances->unit_price * ($sharesBalances->value) / $sharesBalances->value;
                else return 0;
            })
            ->addColumn('formatted_created_at', function ($sharesBalances) {
                return Carbon::parse($sharesBalances->created_at)->format(config('app.date_format'));
            })
            ->addColumn('total_shares', function ($sharesBalances) {
                return $sharesBalances->value;
            })
            ->addColumn('present_value', function ($sharesBalances) {
                return number_format(($sharesBalances->value) * actualActionValue(getSelledActions(true)), 2);
            })
            ->addColumn('current_earnings', function ($sharesBalances) {
                return number_format(($sharesBalances->value) * actualActionValue(getSelledActions(true)) - $sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('value_format', function ($sharesBalances) {
                return number_format($sharesBalances->value, 0);
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->rawColumns(['total_price', 'share_price', 'formatted_created_at', 'total_shares', 'present_value', 'current_earnings', 'value_format'])
            ->make(true);
    }
}

