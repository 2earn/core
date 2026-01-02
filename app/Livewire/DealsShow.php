<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Services\CommissionBreakDownService;
use App\Services\Deals\DealService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsShow extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';
    const CURRENCY = '$';

    protected CommissionBreakDownService $commissionService;
    protected DealService $dealService;

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

    public function boot(CommissionBreakDownService $commissionService, DealService $dealService)
    {
        $this->commissionService = $commissionService;
        $this->dealService = $dealService;
    }

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
            $dealService = app(DealService::class);
            $dealService->delete($id);
            return redirect()->route(self::INDEX_ROUTE_NAME, $paramRedirect)
                ->with('success', Lang::get('Deal Deleted Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, $paramRedirect)
                ->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        $deal = $this->dealService->findById($this->idDeal);
        if (is_null($deal)) {
            $this->redirect()->route('deals_index', ['locale' => app()->getLocale()]);
        }

        $commissions = $this->commissionService->getByDealId($this->idDeal, 'id', 'ASC');

        $totals = $this->commissionService->calculateTotals($this->idDeal);
        $this->jackpot = $totals['jackpot'];
        $this->earn_profit = $totals['earn_profit'];
        $this->proactive_cashback = $totals['proactive_cashback'];
        $this->tree_remuneration = $totals['tree_remuneration'];

        $params = [
            'deal' => $deal,
            'commissions' => $commissions
        ];
        return view('livewire.deals-show', $params)->extends('layouts.master')->section('content');
    }
}
