<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Core\Enum\DealStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class DealsCreateUpdate extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';
    public $idDeal, $name, $description, $status, $objective_turnover, $start_date, $end_date, $out_provider_turnover, $items_profit_average, $initial_commission, $final_commission, $precision, $progressive_commission,
        $margin_percentage, $cash_back_margin_percentage, $proactive_consumption_margin_percentage, $shareholder_benefits_margin_percentage, $tree_margin_percentage, $current_turnover, $item_price, $current_turnover_index, $created_by;
    public $update = false;
    public $statusList;


    protected $rules = [
        'name' => 'required',
    ];

    public function mount(Request $request)
    {
        $this->idDeal = $request->input('id');
        if (!is_null($this->idDeal)) {
            $this->edit($this->idDeal);
        } else {
            $this->init();
        }
    }

    public function init()
    {
        $this->status = DealStatus::New->value;
    }

    public function edit($idDeal)
    {
        $this->idDeal = $idDeal;
        $deal = Deal::find($idDeal);
        $this->name = $deal->name;
        $this->status = $deal->status;
        $this->description = $deal->description;
        $this->objective_turnover = $deal->objective_turnover;
        $this->start_date = $deal->start_date;
        $this->end_date = $deal->end_date;
        $this->out_provider_turnover = $deal->out_provider_turnover;
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
        $this->current_turnover = $deal->current_turnover;
        $this->item_price = $deal->item_price;
        $this->current_turnover_index = $deal->current_turnover_index;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(), 'id' => $this->idDeal])->with('warning', Lang::get('Deal operation cancelled!!'));
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
            'out_provider_turnover' => $this->out_provider_turnover,
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
            'current_turnover' => $this->current_turnover,
            'item_price' => $this->item_price,
            'current_turnover_index' => $this->current_turnover_index,
            'created_by' => auth()->user()->id
        ];
        try {
            Deal::where('id', $this->idDeal)
                ->update($params);
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Deal!!'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Updated Successfully!!'));

    }

    public function store()
    {
        $this->validate();
        $params = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'objective_turnover' => $this->objective_turnover,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'out_provider_turnover' => $this->out_provider_turnover,
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
            'current_turnover' => $this->current_turnover,
            'item_price' => $this->item_price,
            'current_turnover_index' => $this->current_turnover_index,
            'created_by' => auth()->user()->id,
        ];
        try {
            Deal::create($params);
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(),])->with('danger', Lang::get('Something goes wrong while creating Deal!!'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Created Successfully!!'));
    }

    public function render()
    {
        return view('livewire.deals-create-update')->extends('layouts.master')->section('content');
    }
}
