<?php

namespace App\Http\Controllers;

use App\Services\Balances\Balances;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\DB;

class UserssController extends Controller
{
    const PERCENTAGE = ' % ';

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
        if (!$user) $user->idUser = '';
        $query = DB::table('bfss_balances as ub')
            ->select(
                DB::raw('RANK() OVER (ORDER BY ub.created_at ASC) as ranks'),
                'ub.beneficiary_id', 'ub.id', 'ub.operator_id', 'ub.reference', 'ub.created_at', 'bo.operation', 'ub.description',
                DB::raw(" CASE WHEN ub.operator_id = '11111111' THEN 'system' ELSE (SELECT CONCAT(IFNULL(enfirstname, ''), ' ', IFNULL(enlastname, '')) FROM metta_users mu WHERE mu.idUser = ub.beneficiary_id) END AS source "),
                DB::raw(" CASE WHEN bo.IO = 'I' THEN CONCAT('+', '$', FORMAT(ub.value, 2)) WHEN bo.IO = 'O' THEN CONCAT('-', '$', FORMAT(ub.value , 2)) WHEN bo.IO = 'IO' THEN 'IO' END AS value "),
                'bo.IO as sensP',
                'ub.percentage as percentage',
                'ub.current_balance',
                'ub.balance_operation_id'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id');
        $query->where('ub.beneficiary_id', $user->idUser);

        if ($type != null && $type != 'ALL') {
            $query->where('percentage', $type);
        };
        return datatables($query->orderBy('created_at')->orderBy('percentage')->get())
            ->editColumn('description', function ($row) {
                return Balances::generateDescriptionById($row->id, BalanceEnum::BFS->value);
            })
            ->editColumn('current_balance', function ($balance) {
                return self::CURRENCY . self::SEPACE . formatSolde($balance->current_balance, 2);
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['description'])
            ->make(true);
    }

    public function getTreeUser($locale)
    {
        return datatables($this->getUserBalancesList($locale, auth()->user()->idUser, BalanceEnum::TREE->value, false))
            ->editColumn('value', function ($balcene) {
                return formatSolde($balcene->value, 2) . ' ' . self::PERCENTAGE;
            })
            ->editColumn('current_balance', function ($balcene) {
                return formatSolde($balcene->current_balance, 2) . ' ' . self::PERCENTAGE;
            })
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->make(true);
    }

    public function getSmsUser($locale)
    {
        return datatables($this->getUserBalancesList($locale, auth()->user()->idUser, BalanceEnum::SMS->value, false))
            ->addColumn('complementary_information', function ($balance) {
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->make(true);
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
                'bo.IO as sensP'
            )
            ->join('balance_operations as bo', 'ub.balance_operation_id', '=', 'bo.id')
            ->where('ub.beneficiary_id', $user->idUser)
            ->orderBy('created_at')->get();

        return datatables($userData)
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
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['description'])
            ->make(true);
    }
}
