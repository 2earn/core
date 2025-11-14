<?php

namespace App\Http\Controllers;

use App\Services\BalanceService;
use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;

class UserssController extends Controller
{
    const PERCENTAGE = ' % ';
    const CURRENCY = '$';
    const SEPACE = ' ';

    public function __construct(
        private readonly settingsManager $settingsManager,
        private readonly BalanceService $balanceService
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
        return datatables($this->getUserBalancesList(app()->getLocale(), auth()->user()->idUser, BalanceEnum::TREE->value, false))
            ->addColumn('reference', function ($balance) {
                return view('parts.datatable.balances-references', ['balance' => $balance]);
            })
            ->editColumn('value', function ($balcene) {
                return formatSolde($balcene->value, 2) . ' ' . self::PERCENTAGE;
            })
            ->editColumn('current_balance', function ($balcene) {
                return formatSolde($balcene->current_balance, 2) . ' ' . self::PERCENTAGE;
            })
            ->addColumn('complementary_information', function ($balance) {
                return getBalanceCIView($balance);
            })
            ->make(true);
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
        if (!$user) $user->idUser = '';
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
            ->where('ub.beneficiary_id', $user->idUser)
            ->orderBy('created_at')->get();

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
}
