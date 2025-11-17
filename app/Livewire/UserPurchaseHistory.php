<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Order;
use Core\Enum\OrderEnum;
use Core\Models\Platform;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class UserPurchaseHistory extends Component
{
    public $allSectors, $allPlatforms, $allDeals, $allItems, $allStatuses, $currentRouteName;

    public $selectedSectorsIds = [];
    public $selectedStatuses = [];
    public $choosenOrders = [];
    public $selectedDealIds = [];
    public $selectedPlatformIds = [];
    public $selectedItemsIds = [];

    public $listeners = [
        'refreshOrders' => 'filterOrders'
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        $userId = auth()->user()->id;

        $this->allSectors = BusinessSector::whereHas('platforms.items.orderDetails.order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->distinct()
            ->get();

        $this->allPlatforms = Platform::whereHas('items.orderDetails.order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->distinct()
            ->get();

        $this->allDeals = Deal::with('items')
            ->whereHas('items.orderDetails.order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->distinct()
            ->get();

        $this->allItems = Item::whereHas('OrderDetails.order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->distinct()
            ->get();

        $this->allStatuses = OrderEnum::cases();

        $this->filterOrders();
    }

    public function prepareQuery()
    {
        $query = Order::query();
        $query->where('user_id', auth()->user()->id);

        if (!empty($this->selectedStatuses)) {
            $query->whereIn('status', $this->selectedStatuses);
        }

        if (!empty($this->selectedPlatformIds)) {
            $query->whereHas('OrderDetails.item', function ($q) {
                $q->whereIn('platform_id', $this->selectedPlatformIds);
            });
        }

        if (!empty($this->selectedDealIds)) {
            $query->whereHas('OrderDetails.item', function ($q) {
                $q->whereIn('deal_id', $this->selectedDealIds);
            });
        }

        if (!empty($this->selectedItemsIds)) {
            $query->whereHas('OrderDetails.item', function ($q) {
                $q->whereIn('id', $this->selectedItemsIds);
            });
        }
        if (!empty($this->selectedSectorsIds)) {
            $query->whereHas('OrderDetails.item.platform.businessSector', function ($q) {
                $q->whereIn('id', $this->selectedSectorsIds);
            });
        }

        $query->orderBy('created_at', 'ASC');

        return $query->get();
    }

    public function filterOrders()
    {
        $this->choosenOrders = $this->prepareQuery();
    }

    public function render()
    {
        return view('livewire.user-purchase-history')->extends('layouts.master')->section('content');
    }
}






