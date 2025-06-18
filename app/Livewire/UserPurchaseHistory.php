<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Core\Enum\OrderEnum;
use Core\Models\Platform;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class UserPurchaseHistory extends Component
{
    public $allPlatforms, $allStatuses, $currentRouteName;
    public $selectedStatuses = [];
    public $choosenOrders = [];
    public $selectedPlatforms = [];
    public $listeners = [
        'refreshOrders' => 'filterOrders'
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        if (User::isSuperAdmin()) {
            $this->allPlatforms = Platform::all();
        } else {
            $this->allPlatforms = Platform::where(function ($query) {
                $query->where('financial_manager_id', '=', auth()->user()->id)
                    ->orWhere('marketing_manager_id', '=', auth()->user()->id)
                    ->orWhere('owner_id', '=', auth()->user()->id);
            })->get();
        }
        $this->allStatuses = OrderEnum::cases();
        $this->filterOrders();
    }

    public function prepareQuery()
    {
        $query = Order::query();

        if (!empty($this->selectedStatuses)) {
            $query->whereIn('status', $this->selectedStatuses);
        }

        return $query->orderBy('created_at', 'ASC')->get();
    }

    public function filterOrders()
    {
        $this->choosenOrders = $this->prepareQuery();
        $this->dispatch('updateOrdersDatatable', []);
    }

    public function render()
    {
        return view('livewire.user-purchase-history')->extends('layouts.master')->section('content');
    }
}






