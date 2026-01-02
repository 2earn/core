<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Item;
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

    protected $paginationTheme = 'bootstrap';

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->endDate = now()->addDay()->format('Y-m-d');
        $this->startDate = now()->subDays(365)->format('Y-m-d');

        $this->loadDeals();
        $this->loadStatistics();
    }

    public function loadDeals()
    {
        $this->deals = Deal::select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function loadProducts()
    {
        $query = Item::select('id', 'name', 'ref')
            ->where('ref', '!=', '#0001')
            ->orderBy('name');

        if ($this->dealId) {
            $query->where('deal_id', $this->dealId);
        }

        $this->products = $query->get();
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
        $this->endDate = now()->addDay()->format('Y-m-d');
        $this->startDate = now()->subDays(365)->format('Y-m-d');
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


