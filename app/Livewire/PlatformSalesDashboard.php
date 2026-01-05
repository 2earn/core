<?php

namespace App\Livewire;

use App\Services\Dashboard\SalesDashboardService;
use App\Services\Platform\PlatformService;
use App\Models\Platform;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PlatformSalesDashboard extends Component
{
    public $platformId;
    public $platform;
    public $startDate;
    public $endDate;
    public $viewMode = 'daily';
    public $kpis = [];
    public $chartData = [];
    public $loading = false;

    protected $queryString = ['startDate', 'endDate', 'viewMode'];

    protected SalesDashboardService $dashboardService;
    protected PlatformService $platformService;

    public function boot(
        SalesDashboardService $dashboardService,
        PlatformService $platformService
    ) {
        $this->dashboardService = $dashboardService;
        $this->platformService = $platformService;
    }

    public function mount($platformId)
    {
        $this->platformId = $platformId;
        $this->platform = Platform::with(['logoImage', 'businessSector'])->findOrFail($platformId);

        if (!$this->startDate) {
            $this->startDate = now()->subDays(30)->format('Y-m-d');
        }
        if (!$this->endDate) {
            $this->endDate = now()->format('Y-m-d');
        }
        if (!$this->viewMode) {
            $this->viewMode = 'daily';
        }

        $this->loadKpis();
        $this->loadChartData();
    }

    public function loadKpis()
    {
        $this->loading = true;

        try {
            $filters = [
                'platform_id' => $this->platformId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value) && $value !== '';
            });

            $this->kpis = $this->dashboardService->getKpiData($filters);

            Log::info('[PlatformSalesDashboard] KPIs loaded successfully', [
                'platform_id' => $this->platformId,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            Log::error('[PlatformSalesDashboard] Error loading KPIs: ' . $e->getMessage());
            $this->kpis = [
                'total_sales' => 0,
                'orders_in_progress' => 0,
                'orders_successful' => 0,
                'orders_failed' => 0,
                'total_customers' => 0,
            ];
            session()->flash('error', __('Error loading sales data'));
        } finally {
            $this->loading = false;
        }
    }

    public function loadChartData()
    {
        try {
            $filters = [
                'platform_id' => $this->platformId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'view_mode' => $this->viewMode,
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value) && $value !== '';
            });

            $result = $this->dashboardService->getSalesEvolutionChart($filters);
            $this->chartData = $result['chart_data'];

            Log::info('[PlatformSalesDashboard] Chart data loaded successfully', [
                'platform_id' => $this->platformId,
                'view_mode' => $this->viewMode,
                'data_points' => count($this->chartData)
            ]);

            $this->dispatch('chartDataUpdated', $this->chartData);
        } catch (\Exception $e) {
            Log::error('[PlatformSalesDashboard] Error loading chart data: ' . $e->getMessage());
            $this->chartData = [];
            session()->flash('error', __('Error loading chart data'));
        }
    }

    public function updatedStartDate()
    {
        $this->validateDates();
        $this->loadKpis();
        $this->loadChartData();
    }

    public function updatedEndDate()
    {
        $this->validateDates();
        $this->loadKpis();
        $this->loadChartData();
    }

    public function updatedViewMode()
    {
        $this->loadChartData();
    }

    private function validateDates()
    {
        if ($this->startDate && $this->endDate) {
            if (strtotime($this->startDate) > strtotime($this->endDate)) {
                $this->endDate = $this->startDate;
            }
        }
    }

    public function resetFilters()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->viewMode = 'daily';
        $this->loadKpis();
        $this->loadChartData();
    }

    public function render()
    {
        return view('livewire.platform-sales-dashboard')
            ->extends('layouts.master')
            ->section('content');
    }
}

