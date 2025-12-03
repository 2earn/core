<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Order;
use App\Services\Orders\OrderService;
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
        $orderService = app(OrderService::class);
        return $orderService->getUserPurchaseHistoryQuery(
            auth()->user()->id,
            $this->selectedStatuses,
            $this->selectedPlatformIds,
            $this->selectedDealIds,
            $this->selectedItemsIds,
            $this->selectedSectorsIds
        );
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
        $chosenOrders = $this->prepareQuery()->paginate(5);

        return view('livewire.user-purchase-history', [
            'chosenOrders' => $chosenOrders
        ])->extends('layouts.master')->section('content');
    }
}






