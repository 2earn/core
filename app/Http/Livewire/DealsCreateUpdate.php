<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Core\Enum\DealStatus;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
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
        $objective_turnover,
        $start_date,
        $end_date,
        $provider_turnover,
        $items_profit_average,
        $initial_commission,
        $final_commission,
        $precision,
        $progressive_commission,
        $margin_percentage,
        $cash_back_margin_percentage,
        $proactive_consumption_margin_percentage,
        $shareholder_benefits_margin_percentage,
        $tree_margin_percentage,
        $discount,
        $item_price,
        $created_by;
    public $update = false;
    public $statusList;


    protected $rules = [
        'name' => 'required|min:5',
        'description' => 'required|min:5',
        'start_date' => ['required', 'after_or_equal:today'],
        'end_date' => ['required', 'after:start_date'],
    ];

    public function mount(Request $request)
    {
        $this->idDeal = $request->input('id');
        if (!is_null($this->idDeal)) {
            $this->edit();
        } else {
            $this->idPlatform = $request->input('idPlatform');
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
        $this->precision = $this->getDealParam('DEALS_PRECISION');
        $this->cash_back_margin_percentage = $this->getDealParam('DEALS_CASH_BACK_MARGIN_PERCENTAGE');
        $this->proactive_consumption_margin_percentage = $this->getDealParam('DEALS_PROACTIVE_CONSUMPTION_MARGIN_PERCENTAGE');
        $this->shareholder_benefits_margin_percentage = $this->getDealParam('DEALS_SHAREHOLDER_BENEFITS_MARGIN_PERCENTAGE');
        $this->tree_margin_percentage = $this->getDealParam('DEALS_TREE_MARGIN_PERCENTAGE');
        $this->status = DealStatus::New->value;
        $this->objective_turnover =
        $this->start_date = $this->end_date =
        $this->provider_turnover =
        $this->items_profit_average =
        $this->initial_commission =
        $this->final_commission =
        $this->progressive_commission =
        $this->margin_percentage =
        $this->discount = 10;
    }

    public function edit()
    {
        $deal = Deal::find($this->idDeal);
        $this->name = $deal->name;
        $this->status = $deal->status;
        $this->description = $deal->description;
        $this->objective_turnover = $deal->objective_turnover;
        $this->start_date = $deal->start_date;
        $this->end_date = $deal->end_date;
        $this->provider_turnover = $deal->provider_turnover;
        $this->items_profit_average = $deal->items_profit_average;
        $this->initial_commission = $deal->initial_commission;
        $this->final_commission = $deal->final_commission;
        $this->precision = $deal->precision;
        $this->progressive_commission = $deal->progressive_commission;
        $this->margin_percentage = $deal->margin_percentage;
        $this->cash_back_margin_percentage = $deal->cash_back_margin_percentage;
        $this->proactive_consumption_margin_percentage = $deal->proactive_consumption_margin_percentage;
        $this->shareholder_benefits_margin_percentage = $deal->shareholder_benefits_margin_percentage;
        $this->tree_margin_percentage = $deal->tree_margin_percentage;
        $this->discount = $deal->discount;
        $this->idPlatform = $deal->platform_id;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(), 'id' => $this->idDeal])->with('warning', Lang::get('Deal operation cancelled'));
    }

    public function update()
    {
        $this->validate();
        $params = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'objective_turnover' => $this->objective_turnover,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'provider_turnover' => $this->provider_turnover,
            'items_profit_average' => $this->items_profit_average,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'precision' => $this->precision,
            'progressive_commission' => $this->progressive_commission,
            'margin_percentage' => $this->margin_percentage,
            'cash_back_margin_percentage' => $this->cash_back_margin_percentage,
            'proactive_consumption_margin_percentage' => $this->proactive_consumption_margin_percentage,
            'shareholder_benefits_margin_percentage' => $this->shareholder_benefits_margin_percentage,
            'tree_margin_percentage' => $this->tree_margin_percentage,
            'discount' => $this->discount,
            'created_by_id' => auth()->user()->id
        ];
        try {
            Deal::where('id', $this->idDeal)->update($params);
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Deal'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Updated Successfully'));

    }

    public function store()
    {
        $this->validate();
        $params = [
            'name' => $this->name,
            'validated' => false,
            'description' => $this->description,
            'status' => $this->status,
            'objective_turnover' => $this->objective_turnover,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'provider_turnover' => $this->provider_turnover,
            'items_profit_average' => $this->items_profit_average,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'precision' => $this->precision,
            'progressive_commission' => $this->progressive_commission,
            'margin_percentage' => $this->margin_percentage,
            'cash_back_margin_percentage' => $this->cash_back_margin_percentage,
            'proactive_consumption_margin_percentage' => $this->proactive_consumption_margin_percentage,
            'shareholder_benefits_margin_percentage' => $this->shareholder_benefits_margin_percentage,
            'tree_margin_percentage' => $this->tree_margin_percentage,
            'discount' => $this->discount,
            'created_by_id' => auth()->user()->id,
            'platform_id' => $this->idPlatform,
        ];
        try {
            Deal::create($params);
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(),])->with('danger', Lang::get('Something goes wrong while creating Deal'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Created Successfully'));
    }

    public function render()
    {
        $platform = Platform::find($this->idPlatform);
        return view('livewire.deals-create-update', ['platform' => $platform])->extends('layouts.master')->section('content');
    }
}
