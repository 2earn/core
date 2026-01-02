<?php

namespace App\Http\Controllers;

use App\Enums\BalanceEnum;
use App\Services\Balances\BalanceService;
use App\Services\Balances\BalanceTreeService;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;

class UserssController extends Controller
{
    const PERCENTAGE = ' % ';
    const CURRENCY = '$';
    const SEPACE = ' ';

    public function __construct(
        private readonly settingsManager $settingsManager,
        private readonly BalanceService $balanceService,
        private readonly BalanceTreeService $treeService
    ) {
    }

    public function invitations()
    {
        $user = $this->settingsManager->getAuthUser();
        $userData = DB::select(getSqlFromPath('get_invitations_user'), [$user->idUser, $user->idUser, $user->idUser, $user->idUser, $user->idUser]);
        return datatables($userData)
            ->make(true);
    }


    public function getPurchaseBFSUser($type)
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) {
            $user = (object)['idUser' => ''];
        }

        return $this->balanceService->getPurchaseBFSUserDatatables($user->idUser, $type);
    }

    public function getTreeUser()
    {
        return $this->treeService->getTreeUserDatatables(auth()->user()->idUser);
    }

    public function getSmsUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) {
            $user = (object)['idUser' => ''];
        }

        return $this->balanceService->getSmsUserDatatables($user->idUser);
    }

    public function getUserBalancesList($locale, $idUser, $idamount, $json = true)
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

    public function getChanceUser()
    {
        $user = $this->settingsManager->getAuthUser();
        if (!$user) {
            $user = (object)['idUser' => ''];
        }

        return $this->balanceService->getChanceUserDatatables($user->idUser);
    }
}
