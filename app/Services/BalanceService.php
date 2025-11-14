<?php

namespace App\Services;

use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
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
}

