<?php

namespace App\Http\Controllers;

use App\Services\Balances\BalanceService;
use App\Services\Balances\ShareBalanceService;
use App\Services\Settings\SettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;

class SharesController extends Controller
{
    public function __construct(
        private readonly BalanceService $balanceService,
        private readonly SettingService $settingService,
        private readonly ShareBalanceService $shareBalanceService
    ) {
    }

    public function getActionHistorysQuery()
    {
        return DB::table('action_history')
            ->select('id', 'title', 'reponce');
    }

    public function list($idUser)
    {
        $results = $this->shareBalanceService->getShareBalancesList($idUser);
        return response()->json($results);
    }

    public function index()
    {
        return datatables($this->getActionHistorysQuery())
            ->addColumn('action', function ($share) {
                return view('parts.datatable.share-history-action', ['share' => $share]);
            })
            ->editColumn('reponce', function ($share) {
                return view('parts.datatable.share-history-reponce', ['share' => $share]);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function getSharesSolde()
    {
        $userId = auth()->user()->idUser ?? '';
        return $this->balanceService->getSharesSoldeDatatables($userId);
    }

    public function getSharesSoldesQuery()
    {
        return $this->shareBalanceService->getSharesSoldesQuery();
    }

    public function getSharesSoldes()
    {
        return datatables($this->getSharesSoldesQuery())
            ->addColumn('total_price', function ($sharesBalances) {
                return number_format($sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('share_price', function ($sharesBalances) {
                if ($sharesBalances->value != 0)
                    return $sharesBalances->unit_price * ($sharesBalances->value) / $sharesBalances->value;
                else return 0;
            })
            ->addColumn('flag', function ($sharesBalances) {
                return '<img src="' . $this->getFormatedFlagResourceName($sharesBalances->apha2) . '" alt="' . strtolower($sharesBalances->apha2) . '" class="avatar-xxs me-2">';
            })
            ->addColumn('sell_price_now', function ($sharesBalances) {
                return number_format(actualActionValue(getSelledActions(true)) * ($sharesBalances->value), 2);
            })
            ->addColumn('gain', function ($sharesBalances) {
                return number_format(actualActionValue(getSelledActions(true)) * ($sharesBalances->value) - $sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('total_shares', function ($sharesBalances) {
                return number_format($sharesBalances->value, 0);
            })
            ->addColumn('asset', function ($sharesBalances) {
                return $this->getFormatedFlagResourceName($sharesBalances->apha2);
            })
            ->rawColumns(['flag', 'share_price', 'status'])
            ->make(true);
    }

    public function getSharePriceEvolution()
    {
        $query = $this->getSharePriceEvolutionQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDateQuery()
    {
        return $this->shareBalanceService->getSharePriceEvolutionDateQuery();
    }

    public function getSharePriceEvolutionDate()
    {
        $query = $this->getSharePriceEvolutionDateQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionWeekQuery()
    {
        return $this->shareBalanceService->getSharePriceEvolutionWeekQuery();
    }

    public function getSharePriceEvolutionWeek()
    {
        $query = $this->getSharePriceEvolutionWeekQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionMonthQuery()
    {
        return $this->shareBalanceService->getSharePriceEvolutionMonthQuery();
    }

    public function getSharePriceEvolutionMonth()
    {
        $query = $this->getSharePriceEvolutionMonthQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionDayQuery()
    {
        return $this->shareBalanceService->getSharePriceEvolutionDayQuery();
    }

    public function getSharePriceEvolutionDay()
    {
        $query = $this->getSharePriceEvolutionDayQuery();
        foreach ($query as $record) {
            $record->y = (float)$record->y;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionUser()
    {
        $query = DB::select(getSqlFromPath('get_share_price_evolution_user'), [auth()->user()->idUser, auth()->user()->idUser]);
        foreach ($query as $record) {
            if ($record->y) $record->y = (float)$record->y;
            $record->x = (float)$record->x;
        }
        return response()->json($query);
    }

    public function getSharePriceEvolutionQuery()
    {
        return $this->shareBalanceService->getSharePriceEvolutionQuery();
    }

    public function getFormatedFlagResourceName($flagName)
    {
        return Vite::asset("resources/images/flags/" . strtolower($flagName) . ".svg");
    }


    public function getSharesSoldeQuery()
    {
        return $this->shareBalanceService->getSharesSoldeQuery(Auth()->user()->idUser);
    }


    public function getActionValues()
    {
        $limit = getSelledActions(true) * 1.05;
        $data = [];
        $settingValues = $this->settingService->getIntegerValues(['16', '17', '18']);
        $initial_value = $settingValues['16'];
        $final_value = $initial_value * 5;
        $total_actions = $settingValues['18'];

        for ($x = 0; $x <= $limit; $x += intval($limit / 20)) {
            $val = ($final_value - $initial_value) / ($total_actions - 1) * ($x + 1) + ($initial_value - ($final_value - $initial_value) / ($total_actions - 1));
            $data[] = [
                'x' => $x,
                'y' => number_format($val, 2, '.', '') * 1
            ];
        }
        return response()->json($data);

    }
}
