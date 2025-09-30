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
