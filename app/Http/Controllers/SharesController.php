<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
}
