<?php

namespace App\Livewire;

use App\Services\BusinessSector\BusinessSectorService;
use App\Services\Deals\DealService;
use App\Services\Items\ItemService;
use App\Services\Orders\OrderService;
use App\Services\Platform\PlatformService;
use Core\Enum\OrderEnum;
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

    protected BusinessSectorService $businessSectorService;
    protected PlatformService $platformService;
    protected DealService $dealService;
    protected ItemService $itemService;
    protected OrderService $orderService;

    public function boot(
        BusinessSectorService $businessSectorService,
        PlatformService $platformService,
        DealService $dealService,
        ItemService $itemService,
        OrderService $orderService
    ) {
        $this->businessSectorService = $businessSectorService;
        $this->platformService = $platformService;
        $this->dealService = $dealService;
        $this->itemService = $itemService;
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        $userId = auth()->user()->id;

        $this->allSectors = $this->businessSectorService->getSectorsWithUserPurchases($userId);
        $this->allPlatforms = $this->platformService->getPlatformsWithUserPurchases($userId);
        $this->allDeals = $this->dealService->getDealsWithUserPurchases($userId);
        $this->allItems = $this->itemService->getItemsWithUserPurchases($userId);
        $this->allStatuses = OrderEnum::cases();
    }

    public function prepareQuery()
    {
        return $this->orderService->getUserPurchaseHistoryQuery(
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






