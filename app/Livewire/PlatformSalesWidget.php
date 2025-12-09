<?php

namespace App\Livewire;

use App\Services\Dashboard\SalesDashboardService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PlatformSalesWidget extends Component
{
    public $platformId;
    public $startDate;
    public $endDate;
    public $kpis = [];
    public $showFilters = false;

    protected SalesDashboardService $dashboardService;

    public function boot(SalesDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function mount($platformId, $startDate = null, $endDate = null, $showFilters = false)
    {
        $this->platformId = $platformId;
        $this->showFilters = $showFilters;

        $this->startDate = $startDate ?? now()->subDays(30)->format('Y-m-d');
        $this->endDate = $endDate ?? now()->format('Y-m-d');

        $this->loadKpis();
    }

    public function loadKpis()
    {
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
        } catch (\Exception $e) {
            Log::error('[PlatformSalesWidget] Error loading KPIs: ' . $e->getMessage());
            $this->kpis = [
                'total_sales' => 0,
                'orders_in_progress' => 0,
                'orders_successful' => 0,
                'orders_failed' => 0,
                'total_customers' => 0,
            ];
        }
    }

    public function updatedStartDate()
    {
        $this->loadKpis();
    }

    public function updatedEndDate()
    {
        $this->loadKpis();
    }

    public function render()
    {
        return view('livewire.platform-sales-widget');
    }
}

