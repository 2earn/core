<?php

namespace App\Livewire;

use App\Services\Deals\DealService;
use App\Services\Items\ItemService;
use App\Services\Orders\OrderService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class OrderDashboard extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $dealId = null;
    public $productId = null;
    public $userId = null;

    public $statistics = [];
    public $deals = [];
    public $products = [];

    public $loading = false;
    public $perPage = 20;

    protected $orderService;
    protected $dealService;
    protected $itemService;

    protected $paginationTheme = 'bootstrap';

    public function boot(OrderService $orderService, DealService $dealService, ItemService $itemService)
    {
        $this->orderService = $orderService;
        $this->dealService = $dealService;
        $this->itemService = $itemService;
    }

    public function mount()
    {
        $this->endDate = now()->format('Y-m-d');
        $this->startDate = now()->subDays(100)->format('Y-m-d');
        $this->loadDeals();
        $this->loadStatistics();
    }

    public function loadDeals()
    {
        $this->deals = $this->dealService->getAllDeals();
    }

    public function loadProducts()
    {
        $this->products = $this->itemService->getItemsByDeal($this->dealId);
    }

    public function updatedDealId()
    {
        $this->productId = null;
        $this->loadProducts();
        $this->loadStatistics();
    }

    public function updatedProductId()
    {
        $this->loadStatistics();
    }

    public function updatedStartDate()
    {
        $this->loadStatistics();
    }

    public function updatedEndDate()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        try {
            $this->loading = true;

            $this->statistics = $this->orderService->getOrderDashboardStatistics(
                $this->startDate,
                $this->endDate,
                $this->dealId,
                $this->productId,
                $this->userId
            );

            $this->loading = false;
        } catch (\Exception $e) {
            Log::error('[OrderDashboard] Failed to load statistics: ' . $e->getMessage());
            $this->loading = false;
            session()->flash('error', 'Failed to load statistics: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->endDate = now()->format('Y-m-d');
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->dealId = null;
        $this->productId = null;
        $this->products = [];
        $this->loadStatistics();
    }

    public function render()
    {
        return view('livewire.order-dashboard')->extends('layouts.master')->section('content');
    }
}

