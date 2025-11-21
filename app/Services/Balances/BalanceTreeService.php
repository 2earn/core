<?php

namespace App\Services\Balances;

use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class BalanceTreeService
{
    const PERCENTAGE = ' % ';

    /**
     * Get tree user balances datatables
     *
     * @param int $userId
     * @return mixed
     */
    public function getTreeUserDatatables($userId)
    {
        $balances = $this->getUserBalancesList($userId, BalanceEnum::TREE->value);

        return datatables($balances)
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->editColumn('value', function ($balance) {
                return formatSolde($balance->value, 2) . ' ' . self::PERCENTAGE;
            })
            ->editColumn('current_balance', function ($balance) {
                return formatSolde($balance->current_balance, 2) . ' ' . self::PERCENTAGE;
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->make(true);
    }

    /**
     * Get user balances list
     *
     * @param int $idUser
     * @param int $idamount
     * @return \Illuminate\Support\Collection
     */
    public function getUserBalancesList($idUser, $idamount)
    {
        $balances = match (intval($idamount)) {
            BalanceEnum::CASH->value => "cash_balances",
            BalanceEnum::BFS->value => "bfss_balances",
            BalanceEnum::DB->value => "discount_balances",
            BalanceEnum::SMS->value => "sms_balances",
            BalanceEnum::TREE->value => "tree_balances",
            BalanceEnum::SHARE->value => "shares_balances",
            default => "cash_balances",
        };

        return DB::table($balances . ' as u')
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
            ->where('u.beneficiary_id', $idUser)
            ->orderBy('u.created_at', 'DESC')
            ->get();
    }
}

