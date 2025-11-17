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
use Livewire\WithPagination;

class UserPurchaseHistory extends Component
{
    use WithPagination;

    public $allSectors, $allPlatforms, $allDeals, $allItems, $allStatuses, $currentRouteName;

    public $selectedSectorsIds = [];
    public $selectedStatuses = [];
    public $selectedDealIds = [];
    public $selectedPlatformIds = [];
    public $selectedItemsIds = [];

    protected $paginationTheme = 'bootstrap';

    public $listeners = [
        'refreshOrders' => 'resetFilters'
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

        $query->orderBy('created_at', 'DESC');

        return $query;
    }

    public function resetFilters()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        // Reset to first page when filters change
        if (in_array($propertyName, ['selectedSectorsIds', 'selectedStatuses', 'selectedDealIds', 'selectedPlatformIds', 'selectedItemsIds'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $choosenOrders = $this->prepareQuery()->paginate(5);

        return view('livewire.user-purchase-history', [
            'choosenOrders' => $choosenOrders
        ])->extends('layouts.master')->section('content');
    }
}






