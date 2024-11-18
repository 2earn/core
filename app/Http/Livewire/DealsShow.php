<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsShow extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';

    public $listeners = [
        'delete' => 'delete',
        'updateDeal' => 'updateDeal',
    ];

    public $idDeal,
        $currentRouteName,
        $currentTurnover,
        $franchisorMarginPercentage,
        $prescriptorMarginPercentage,
        $influencerMarginPercentage,
        $CashMarginPercentage,
        $supporterMarginPercentage;

    public function mount($id)
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->idDeal = $id;
        $this->currentTurnover = 0; //stocké
        $this->franchisorMarginPercentage = 0;
        $this->prescriptorMarginPercentage = 0;
        $this->influencerMarginPercentage = 0;
        $this->supporterMarginPercentage = 0;
        $this->CashMarginPercentage = 0;
    }

    public function updateDeal($id, $status)
    {
        match (intval($status)) {
            0 => Deal::validateDeal($id),
            2 => Deal::open($id),
            3 => Deal::close($id),
            4 => Deal::archive($id),
        };
    }

    public static function remove($id)
    {
        try {
            Deal::findOrFail($id)->delete();

            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Deleted Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        $deal = Deal::find($this->idDeal);
        if (is_null($deal)) {
            $this->redirect()->route('deals_index', ['locale' => app()->getLocale()]);
        }
        $params = ['deal' => $deal];
        return view('livewire.deals-show', $params)->extends('layouts.master')->section('content');
    }
}
