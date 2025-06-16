<?php

namespace App\Livewire;

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
        $discount;

    public $update = false;

    public $statusList;


    protected $rules = [
        'name' => 'required|min:5',
        'description' => 'required|min:5',
        'target_turnover' => 'required',
        'start_date' => ['required', 'after_or_equal:today'],
        'end_date' => ['required', 'after:start_date'],
    ];

    public function mount(Request $request)
    {

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
        $this->target_turnover = 10000;
        $this->start_date = $this->end_date =
        $this->items_profit_average =
        $this->initial_commission =
        $this->final_commission =
        $this->margin_percentage =
        $this->discount = 10;
        $this->current_turnover = 0;
    }

    public function edit()
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
        $this->idPlatform = $deal->platform_id;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(), 'id' => $this->idDeal])->with('warning', Lang::get('Deal operation cancelled'));
    }

    public function updateDeal()
    {
        $this->validate();
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

    public function store()
    {
        $this->validate();
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
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'discount' => $this->discount,
            'created_by_id' => auth()->user()->id,
            'platform_id' => $this->idPlatform,
        ];
        try {
            Deal::create($params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
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
