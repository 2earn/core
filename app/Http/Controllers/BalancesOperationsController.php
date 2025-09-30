<?php

namespace App\Http\Controllers;

use App\Models\OperationCategory;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;

class BalancesOperationsController extends Controller
{
    public function getBalanceOperationsQuery()
    {
        $select = ['balance_operations.id',
            'balance_operations.operation',
            'balance_operations.io',
            'balance_operations.ref',
            'balance_operations.source',
            'balance_operations.amounts_id',
            'balance_operations.note',
            'balance_operations.parent_id',
            'balance_operations.operation_category_id',
            'balance_operations.modify_amount',
            'balance_operations.relatable',
            'balance_operations.relatable_model',
            'balance_operations.relatable_type',
            'amounts.amountsshortname'
        ];
        return DB::table('balance_operations')
            ->leftJoin('amounts', 'balance_operations.amounts_id', '=', 'amounts.idamounts')
            ->select($select);
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
            ->editColumn('amounts_id', function ($balance) {
                return view('parts.datatable.balances-amounts-id', ['ammount' => $balance->amounts_id]);
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
