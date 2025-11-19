<?php

namespace App\Http\Controllers;

use App\Services\Balances\BalanceService;
use Core\Enum\BalanceEnum;
use Core\Services\BalancesManager;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersBalancesController extends Controller
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


    public function index($typeAmounts, BalanceService $balanceService)
    {
        return $balanceService->getUserBalancesDatatables($typeAmounts);
    }

    public function list($idUser, $idamount, $json = true)
    {
        match (intval($idamount)) {
            BalanceEnum::CASH->value => $balances = "cash_balances",
            BalanceEnum::BFS->value => $balances = "bfss_balances",
            BalanceEnum::DB->value => $balances = "discount_balances",
            BalanceEnum::SMS->value => $balances = "sms_balances",
            BalanceEnum::TREE->value => $balances = "tree_balances",
            BalanceEnum::SHARE->value => $balances = "shares_balances",
            default => $balances = "cash_balances",
        };
        $results = DB::table($balances . ' as u')
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
            ->orderBy('u.created_at', 'DESC')->get();
        if (!$json) {
            return $results;
        }
        return response()->json($results);
    }

    public function getUserCashBalanceQuery()
    {
        return DB::table('cash_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS x'), DB::raw('CAST(current_balance AS DECIMAL(10,2)) AS y'))
            ->where('beneficiary_id', auth()->user()->idUser)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getUserCashBalance()
    {
        $query = $this->getUserCashBalanceQuery();
        foreach ($query as $record) {
            $record->Balance = (float)$record->y;
        }
        return response()->json($query);
    }


    public function updateBalanceStatus(Req $request, BalancesManager $balancesManager)
    {
        try {
            $id = $request->input('id');
            $st = 0;
            DB::table('shares_balances')->where('id', $id)->update(['payed' => $st]);
            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateReserveDate(Req $request)
    {
        try {
            $id = $request->input('id');
            $status = $request->input('status');
            $successArray = ['success' => true];
            if ($status == "true") {
                $st = 1;
                $dt = now();
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st, 'reserved_at' => $dt]);
                return response()->json($successArray);
            } else {
                $st = 0;
                DB::table('user_contacts')->where('id', $id)->update(['availablity' => $st]);
                return response()->json($successArray);
            }
            DB::table('user_contacts')
                ->where('id', $id)
                ->update(['availablity' => $st, 'reserved_at' => $dt]);
            return response()->json($successArray);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function updateBalanceReal(Req $request, BalancesManager $balancesManager)
    {
        try {
            $id = $request->input('id');
            $st = $request->input('amount');
            $total = $request->input('total');

            if ($st == 0) {
                $p = 0;
            } else {
                if ($st < $total) $p = 2;
                if ($st == $total) $p = 1;
            }
            DB::table('shares_balances')
                ->where('id', $id)
                ->update(['real_amount' => floatval($st), 'payed' => $p]);

            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getUpdatedCardContent()
    {
        $updatedContent = number_format(getRevenuSharesReal(), 2); // Adjust this based on your logic
        return response()->json(['value' => $updatedContent]);
    }
}
