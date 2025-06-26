<?php

namespace App\Livewire;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsShow extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';
    const CURRENCY = '$';

    public $listeners = [
        'delete' => 'delete',
        'updateDeal' => 'updateDeal',
    ];

    public $jackpot = 0;
    public $earn_profit = 0;
    public $proactive_cashback = 0;
    public $tree_remuneration = 0;

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
        $this->currentTurnover = 0;
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
        $paramRedirect = ['locale' => app()->getLocale()];
        try {
            Deal::findOrFail($id)->delete();
            return redirect()->route(self::INDEX_ROUTE_NAME, $paramRedirect)->with('success', Lang::get('Deal Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, $paramRedirect)->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        $deal = Deal::find($this->idDeal);
        if (is_null($deal)) {
            $this->redirect()->route('deals_index', ['locale' => app()->getLocale()]);
        }
        $commissions = CommissionBreakDown::where('deal_id', $this->idDeal)
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($commissions as $commission) {
            $this->jackpot = $commission->cash_jackpot;
            $this->earn_profit = $commission->cash_company_profit;
            $this->proactive_cashback = $commission->cash_cashback;
            $this->tree_remuneration = $commission->cash_tree;
        }


        $params = [
            'deal' => $deal,
            'commissions' => $commissions
        ];
        return view('livewire.deals-show', $params)->extends('layouts.master')->section('content');
    }
}
