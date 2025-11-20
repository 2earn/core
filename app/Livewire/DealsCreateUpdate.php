<?php

namespace App\Livewire;

use App\Models\CommissionFormula;
use App\Models\Deal;
use Core\Enum\DealStatus;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsCreateUpdate extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';
    public
        $idDeal,
        $idPlatform,
        $name,
        $description,
        $status,
        $current_turnover,
        $target_turnover,
        $start_date,
        $end_date,
        $items_profit_average,
        $initial_commission,
        $final_commission,
        $commission_formula_id,
        $discount;

    public $earn_profit,
        $jackpot,
        $tree_remuneration,
        $proactive_cashback;

    public $update = false;

    public $statusList;

    public $commissionFormulas;


    protected $rules = [
        'name' => 'required|min:5',
        'description' => 'required|min:5',
        'target_turnover' => 'required',
        'start_date' => ['required', 'after_or_equal:today'],
        'end_date' => ['required', 'after:start_date'],
        'commission_formula_id' => 'required|integer|exists:commission_formulas,id',
    ];

    public function mount(Request $request)
    {
        $this->commissionFormulas = CommissionFormula::where('is_active', true)->get();

        $this->idDeal = $request->input('id');
        if (!is_null($this->idDeal)) {
            $this->edit();
        } else {
            $this->idPlatform = Route::current()->parameter('idPlatform');
            $this->init();
        }
    }

    public function getDealParam($name)
    {
        $param = DB::table('settings')->where("ParameterName", "=", $name)->first();
        if (!is_null($param)) {
            return $param->DecimalValue;
        }
        return 0;
    }

    public function init()
    {
        $this->status = DealStatus::New->value;
        if (count($this->commissionFormulas)) {
            $this->commission_formula_id = $this->commissionFormulas[0]->id;
        } else {
            $this->commission_formula_id = null;

        }
        $this->target_turnover = 1000;
        $this->start_date = $this->end_date =
        $this->items_profit_average =
        $this->margin_percentage =
        $this->discount = 10;
        $this->current_turnover = 0;
        $this->earn_profit = $this->getDealParam('DEALS_EARN_PROFIT_PERCENTAGE');
        $this->jackpot = $this->getDealParam('DEALS_JACKPOT_PERCENTAGE');
        $this->tree_remuneration = $this->getDealParam('DEALS_TREE_REMUNERATION_PERCENTAGE');
        $this->proactive_cashback = $this->getDealParam('DEALS_PROACTIVE_CASHBACK_PERCENTAGE');
    }

    public
    function updatedCommissionFormulaId($commissionFormulaId)
    {
        if ($commissionFormulaId) {
            $formula = CommissionFormula::find($commissionFormulaId);
            if ($formula) {
                $this->initial_commission = $formula->initial_commission;
                $this->final_commission = $formula->final_commission;
            }
        }
    }

    public
    function edit()
    {
        $deal = Deal::find($this->idDeal);
        $this->name = $deal->name;
        $this->status = $deal->status;
        $this->description = $deal->description;
        $this->target_turnover = $deal->target_turnover;
        $this->start_date = $deal->start_date;
        $this->end_date = $deal->end_date;
        $this->items_profit_average = $deal->items_profit_average;
        $this->initial_commission = $deal->initial_commission;
        $this->final_commission = $deal->final_commission;
        $this->discount = $deal->discount;
        $this->earn_profit = $deal->earn_profit;
        $this->jackpot = $deal->jackpot;
        $this->tree_remuneration = $deal->tree_remuneration;
        $this->proactive_cashback = $deal->proactive_cashback;
        $this->idPlatform = $deal->platform_id;
        $this->update = true;
    }

    public
    function cancel()
    {
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(), 'id' => $this->idDeal])->with('warning', Lang::get('Deal operation cancelled'));
    }

    public
    function updateDeal()
    {
        $this->validate();
        $this->updatedCommissionFormulaId($this->commission_formula_id);
        $params = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'target_turnover' => $this->target_turnover,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'items_profit_average' => $this->items_profit_average,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'earn_profit' => $this->earn_profit,
            'tree_remuneration' => $this->tree_remuneration,
            'proactive_cashback' => $this->proactive_cashback,
            'jackpot' => $this->jackpot,
            'discount' => $this->discount,
            'created_by_id' => auth()->user()->id
        ];
        try {
            Deal::where('id', $this->idDeal)->update($params);
        } catch (\Exception $exception) {
            dd($exception);
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Deal'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Updated Successfully'));

    }

    public
    function store()
    {
        $this->validate();

        $this->updatedCommissionFormulaId($this->commission_formula_id);

        $params = [
            'name' => $this->name,
            'validated' => false,
            'description' => $this->description,
            'status' => $this->status,
            'current_turnover' => 0,
            'target_turnover' => $this->target_turnover,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'items_profit_average' => $this->items_profit_average,
            'discount' => $this->discount,
            'earn_profit' => $this->earn_profit,
            'tree_remuneration' => $this->tree_remuneration,
            'proactive_cashback' => $this->proactive_cashback,
            'jackpot' => $this->jackpot,
            'created_by_id' => auth()->user()->id,
            'platform_id' => $this->idPlatform,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
        ];

        try {
            Deal::create($params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(),])->with('danger', Lang::get('Something goes wrong while creating Deal'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Created Successfully'));
    }

    public
    function render()
    {
        $platform = Platform::find($this->idPlatform);
        return view('livewire.deals-create-update', ['platform' => $platform])->extends('layouts.master')->section('content');
    }
}
