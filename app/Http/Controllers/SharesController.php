<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;

class SharesController extends Controller
{

    public function getActionHistorysQuery()
    {
        return DB::table('action_history')
            ->select('id', 'title', 'reponce');
    }

    public function list($idUser)
    {
        $results = DB::table('shares_balances as u')
            ->select(
                'u.reference',
                'u.created_at',
                DB::raw("CASE WHEN b.IO = 'I' THEN u.value ELSE -u.value END AS value"),
                'u.beneficiary_id',
                'u.balance_operation_id',
                'u.real_amount',
                'u.current_balance',
                'u.unit_price',
                'u.total_amount'
            )
            ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
            ->join('users as s', 'u.beneficiary_id', '=', 's.idUser')
            ->where('u.beneficiary_id', $idUser)
            ->orderBy('u.created_at')->get();
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
        return datatables($this->getSharesSoldeQuery())
            ->addColumn('total_price', function ($sharesBalances) {
                return number_format($sharesBalances->unit_price * ($sharesBalances->value), 2);
            })
            ->addColumn('share_price', function ($sharesBalances) {
                if ($sharesBalances->value != 0)
                    return $sharesBalances->unit_price * ($sharesBalances->value) / $sharesBalances->value;
                else return 0;
            })
            ->addColumn('formatted_created_at', function ($sharesBalances) {
                return Carbon::parse($sharesBalances->created_at)->format('Y-m-d H:i:s');
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
                return view('parts.datatable.ci.ci-' . $balance->balance_operation_id, ['balance' => $balance]);
            })
            ->rawColumns(['total_price', 'share_price', 'formatted_created_at', 'total_shares', 'present_value', 'current_earnings', 'value_format'])
            ->make(true);
    }

    public function getSharesSoldesQuery()
    {
        return DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'shares_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'value',
                DB::raw('CAST(shares_balances.unit_price AS DECIMAL(10,2)) AS unit_price'),
                'shares_balances.created_at',
                'shares_balances.payed as payed',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->orderBy('created_at')
            ->get();
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
        return DB::table('shares_balances')
            ->select(DB::raw('DATE(created_at) as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 44)
            ->groupBy('x')
            ->get();
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
        return DB::table('shares_balances')
            ->select(DB::raw(' concat(year(created_at),\'-\',WEEK(created_at, 1)) as x'), DB::raw('SUM(value) as y'), DB::raw(' WEEK(created_at, 1) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
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
        return DB::table('shares_balances')
            ->select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m\') as x'), DB::raw('SUM(value) as y'))
            ->where('balance_operation_id', 44)
            ->groupBy('x')
            ->get();
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
        return DB::table('shares_balances')
            ->select(DB::raw('DAYNAME(created_at) as x'), DB::raw('SUM(value) as y'), DB::raw('DAYOFWEEK(created_at) as z'))
            ->where('balance_operation_id', 44)
            ->groupBy('x', 'z')
            ->orderBy('z')
            ->get();
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
        return DB::table('shares_balances')
            ->select(
                DB::raw('CAST(SUM(value) OVER (ORDER BY id) AS DECIMAL(10,0))AS x'),
                DB::raw('CAST(unit_price AS DECIMAL(10,2)) AS y')
            )
            ->where('balance_operation_id', 44)
            ->orderBy('created_at')
            ->get();
    }

    public function getFormatedFlagResourceName($flagName)
    {
        return Vite::asset("resources/images/flags/" . strtolower($flagName) . ".svg");
    }


    public function getSharesSoldeQuery()
    {
        return DB::table('shares_balances')
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->orderBy('id', 'desc');
    }
}
