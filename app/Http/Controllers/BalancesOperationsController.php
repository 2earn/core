<?php

namespace App\Http\Controllers;

use App\Models\OperationCategory;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;

class BalancesOperationsController extends Controller
{
    public function getBalanceOperationsQuery()
    {
        return BalanceOperation::all();
    }

    public function index()
    {
        return datatables($this->getBalanceOperationsQuery())
            ->addColumn('details', function ($balance) {
                return view('parts.datatable.balances-details', ['balance' => $balance]);
            })
            ->addColumn('action', function ($balance) {
                return view('parts.datatable.balances-status', ['balance' => $balance]);
            })
            ->editColumn('modify_amount', function ($balance) {
                return view('parts.datatable.balances-modify', ['modify' => $balance->modify_amount]);
            })
            ->editColumn('parent_id', function ($balance) {
                return view('parts.datatable.balances-parent', ['balance' => BalanceOperation::find($balance->parent_id)]);
            })
            ->editColumn('operation_category_id', function ($balance) {
                return view('parts.datatable.balances-category', ['category' => OperationCategory::find($balance->operation_category_id)]);
            })
            ->addColumn('others', function ($balance) {
                return view('parts.datatable.balances-others', ['balance' => $balance]);
            })
            ->toJson();
    }

    public function getCategories()
    {
        return datatables(OperationCategory::all())
            ->addColumn('action', function ($operationCategory) {
                return view('parts.datatable.balances-categories-actions', ['operationCategory' => $operationCategory]);
            })
            ->escapeColumns([])
            ->toJson();
    }
}
