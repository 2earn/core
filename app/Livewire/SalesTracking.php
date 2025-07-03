<?php

namespace App\Livewire;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SalesTracking extends Component
{
    public function mount()
    {
        $this->idDeal = Route::current()->parameter('id');
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
        $params = [
            'deal' => $deal,
            'commissions' => $commissions
        ];
        return view('livewire.sales-tracking', $params)->extends('layouts.master')->section('content');
    }
}
