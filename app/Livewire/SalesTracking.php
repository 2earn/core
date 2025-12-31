<?php

namespace App\Livewire;

use App\Services\CommissionBreakDownService;
use App\Services\Deals\DealService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SalesTracking extends Component
{
    public $idDeal;

    protected DealService $dealService;
    protected CommissionBreakDownService $commissionService;

    public function boot(DealService $dealService, CommissionBreakDownService $commissionService)
    {
        $this->dealService = $dealService;
        $this->commissionService = $commissionService;
    }

    public function mount()
    {
        $this->idDeal = Route::current()->parameter('id');
    }

    public function render()
    {
        $deal = $this->dealService->find($this->idDeal);
        if (is_null($deal)) {
            return $this->redirect(route('deals_index', ['locale' => app()->getLocale()]));
        }

        $commissions = $this->commissionService->getByDealId($this->idDeal, 'id', 'ASC');

        $params = [
            'deal' => $deal,
            'commissions' => $commissions
        ];
        return view('livewire.sales-tracking', $params)->extends('layouts.master')->section('content');
    }
}
