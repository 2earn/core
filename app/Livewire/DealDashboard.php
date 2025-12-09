<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\User;
use App\Services\Deals\DealService;
use App\Services\Platform\PlatformService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DealDashboard extends Component
{
    public $userId;
    public $dealId;
    public $deal;
    public $startDate;
    public $endDate;
    public $viewMode = 'daily';

    // Performance data
    public $targetAmount = 0;
    public $currentRevenue = 0;
    public $expectedProgress = 0;
    public $actualProgress = 0;
    public $chartData = [];

    // Loading states
    public $loading = false;
    public $error = null;

    // Available deals for selection
    public $availableDeals = [];
    public $availablePlatforms = [];
    public $selectedPlatformId = null;

    protected DealService $dealService;
    protected PlatformService $platformService;

    public $listeners = [
        'refreshDashboard' => 'loadPerformanceData'
    ];

    protected $queryString = [
        'dealId' => ['except' => null],
        'viewMode' => ['except' => 'daily'],
        'startDate' => ['except' => null],
        'endDate' => ['except' => null],
    ];

    public function boot(DealService $dealService, PlatformService $platformService)
    {
        $this->dealService = $dealService;
        $this->platformService = $platformService;
    }

    public function mount($dealId = null)
    {
        $this->userId = auth()->id();

        // Load available platforms
        if (User::isSuperAdmin()) {
            $this->availablePlatforms = $this->platformService->getEnabledPlatforms();
        } else {
            $this->availablePlatforms = $this->platformService->getPlatformsManagedByUser(
                $this->userId,
                false
            );
        }

        // Load available deals
        $this->loadAvailableDeals();

        // Set deal ID if provided
        if ($dealId) {
            $this->dealId = $dealId;
        } elseif (!empty($this->availableDeals)) {
            // Default to first available deal
            $this->dealId = $this->availableDeals->first()->id;
        }

        // Set default date range (last 30 days)
        if (!$this->startDate) {
            $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        }
        if (!$this->endDate) {
            $this->endDate = Carbon::now()->format('Y-m-d');
        }

        // Load initial data
        if ($this->dealId) {
            $this->loadPerformanceData();
        }
    }

    public function loadAvailableDeals()
    {
        $query = Deal::query();

        if (!User::isSuperAdmin()) {
            $query->whereHas('platform', function ($q) {
                $q->whereHas('managers', function ($q2) {
                    $q2->where('user_id', $this->userId);
                });
            });
        }

        if ($this->selectedPlatformId) {
            $query->where('platform_id', $this->selectedPlatformId);
        }

        $this->availableDeals = $query->where('status', '!=', 4) // Exclude archived
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'platform_id', 'status']);
    }

    public function updatedSelectedPlatformId()
    {
        $this->loadAvailableDeals();

        // Reset deal selection if current deal is not in filtered list
        if ($this->dealId && !$this->availableDeals->contains('id', $this->dealId)) {
            $this->dealId = $this->availableDeals->first()?->id;
        }

        if ($this->dealId) {
            $this->loadPerformanceData();
        }
    }

    public function updatedDealId()
    {
        $this->loadPerformanceData();
    }

    public function updatedViewMode()
    {
        $this->loadPerformanceData();
    }

    public function updatedStartDate()
    {
        if ($this->startDate && $this->endDate && $this->startDate > $this->endDate) {
            $this->error = __('Start date cannot be after end date');
            return;
        }
        $this->loadPerformanceData();
    }

    public function updatedEndDate()
    {
        if ($this->startDate && $this->endDate && $this->startDate > $this->endDate) {
            $this->error = __('End date cannot be before start date');
            return;
        }
        $this->loadPerformanceData();
    }

    public function loadPerformanceData()
    {
        if (!$this->dealId) {
            $this->error = __('Please select a deal');
            return;
        }

        $this->loading = true;
        $this->error = null;

        try {
            $performanceData = $this->dealService->getDealPerformanceChart(
                $this->userId,
                $this->dealId,
                $this->startDate,
                $this->endDate,
                $this->viewMode
            );

            $this->targetAmount = $performanceData['target_amount'] ?? 0;
            $this->currentRevenue = $performanceData['current_revenue'] ?? 0;
            $this->expectedProgress = $performanceData['expected_progress'] ?? 0;
            $this->actualProgress = $performanceData['actual_progress'] ?? 0;
            $this->chartData = $performanceData['chart_data'] ?? [];

            // Load deal details
            $this->deal = Deal::find($this->dealId);

            $this->dispatch('chartDataUpdated', [
                'chartData' => $this->chartData,
                'viewMode' => $this->viewMode
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading deal performance data: ' . $e->getMessage(), [
                'userId' => $this->userId,
                'dealId' => $this->dealId,
                'trace' => $e->getTraceAsString()
            ]);
            $this->error = __('Error loading performance data: ') . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function refreshData()
    {
        $this->loadPerformanceData();
        $this->dispatch('showToast', [
            'type' => 'success',
            'message' => __('Dashboard data refreshed successfully')
        ]);
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->viewMode = 'daily';
        $this->selectedPlatformId = null;
        $this->loadAvailableDeals();
        $this->loadPerformanceData();
    }

    public function render()
    {
        return view('livewire.deal-dashboard', [
            'currency' => config('app.currency', '$')
        ])->extends('layouts.master')->section('content');
    }
}

