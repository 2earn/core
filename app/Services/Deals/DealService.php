<?php

namespace App\Services\Deals;

use App\Models\Deal;
use App\Models\DealChangeRequest;
use App\Models\DealValidationRequest;
use App\Models\PlanLabel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DealService
{
    /**
     * Get deals for a partner user with filters
     *
     * @param int $userId
     * @param int|null $platformId
     * @param string|null $search
     * @param int|null $page
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getPartnerDeals(
        int     $userId,
        ?int    $platformId = null,
        ?string $search = null,
        ?int    $page = null,
        int     $perPage = 5
    )
    {
        $query = Deal::with('platform');

        if (!is_null($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('platform', function ($platformQuery) use ($search) {
                        $platformQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->whereHas('platform', function ($query) use ($userId, $platformId) {
            $query->where(function ($q) use ($userId) {
                $q->where('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            });

            if ($platformId) {
                $query->where('platform_id', $platformId);
            }
        });

        return !is_null($page)
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->get();
    }

    /**
     * Get total count of deals for a partner user
     *
     * @param int $userId
     * @param int|null $platformId
     * @param string|null $search
     * @return int
     */
    public function getPartnerDealsCount(
        int     $userId,
        ?int    $platformId = null,
        ?string $search = null
    ): int
    {
        $query = Deal::query();

        if (!is_null($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('platform', function ($platformQuery) use ($search) {
                        $platformQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->whereHas('platform', function ($query) use ($userId, $platformId) {
            $query->where(function ($q) use ($userId) {
                $q->where('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            });

            if ($platformId) {
                $query->where('platform_id', $platformId);
            }
        });

        return $query->count();
    }

    /**
     * Get a single deal by ID with permission check
     *
     * @param int $dealId
     * @param int $userId
     * @return Deal|null
     */
    public function getPartnerDealById($dealId, $userId): ?Deal
    {
        return Deal::with('platform')
            ->where('id', $dealId)
            ->whereHas('platform', function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('marketing_manager_id', $userId)
                        ->orWhere('financial_manager_id', $userId)
                        ->orWhere('owner_id', $userId);
                });
            })
            ->first();
    }

    /**
     * Enrich deals with change request and validation request data
     *
     * @param Collection|LengthAwarePaginator $deals
     * @return void
     */
    public function enrichDealsWithRequests($deals): void
    {
        $dealsCollection = $deals instanceof LengthAwarePaginator
            ? $deals->getCollection()
            : $deals;

        $dealsCollection->each(function ($deal) {
            $deal->change_requests_count = $this->getDealChangeRequestsCount($deal->id);
            $deal->changeRequests = $this->getDealChangeRequestsLimited($deal->id, 3);
            $deal->validation_requests_count = $this->getDealValidationRequestsCount($deal->id);
            $deal->validationRequests = $this->getDealValidationRequestsLimited($deal->id, 3);
        });
    }

    /**
     * Get count of change requests for a deal
     *
     * @param int $dealId
     * @return int
     */
    public function getDealChangeRequestsCount(int $dealId): int
    {
        return DealChangeRequest::where('deal_id', $dealId)->count();
    }

    /**
     * Get limited change requests for a deal
     *
     * @param int $dealId
     * @param int $limit
     * @return Collection
     */
    public function getDealChangeRequestsLimited(int $dealId, int $limit = 3): Collection
    {
        return DealChangeRequest::where('deal_id', $dealId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get count of validation requests for a deal
     *
     * @param int $dealId
     * @return int
     */
    public function getDealValidationRequestsCount(int $dealId): int
    {
        return DealValidationRequest::where('deal_id', $dealId)->count();
    }

    /**
     * Get limited validation requests for a deal
     *
     * @param int $dealId
     * @param int $limit
     * @return Collection
     */
    public function getDealValidationRequestsLimited(int $dealId, int $limit = 3): Collection
    {
        return DealValidationRequest::where('deal_id', $dealId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get change requests for a deal
     *
     * @param int $dealId
     * @return Collection
     */
    public function getDealChangeRequests(int $dealId): Collection
    {
        return DealChangeRequest::where('deal_id', $dealId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get validation requests for a deal
     *
     * @param int $dealId
     * @return Collection
     */
    public function getDealValidationRequests(int $dealId): Collection
    {
        return DealValidationRequest::where('deal_id', $dealId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user has permission to access a deal
     *
     * @param Deal $deal
     * @param int $userId
     * @return bool
     */
    public function userHasPermission(Deal $deal, int $userId): bool
    {
        return $deal->platform()
            ->where(function ($query) use ($userId) {
                $query->where('marketing_manager_id', $userId)
                    ->orWhere('financial_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            })
            ->exists();
    }

    /**
     * Create a validation request for a deal
     *
     * @param int $dealId
     * @param int $requestedById
     * @param string|null $notes
     * @return DealValidationRequest
     */
    public function createValidationRequest(
        int     $dealId,
        int     $requestedById,
        ?string $notes = null
    ): DealValidationRequest
    {
        return DealValidationRequest::create([
            'deal_id' => $dealId,
            'requested_by_id' => $requestedById,
            'status' => 'pending',
            'notes' => $notes ?? 'Deal validation request created'
        ]);
    }

    /**
     * Create a change request for a deal
     *
     * @param int $dealId
     * @param array $changes
     * @param int $requestedBy
     * @param string $status
     * @return DealChangeRequest
     */
    public function createChangeRequest(
        int    $dealId,
        array  $changes,
        int    $requestedBy,
        string $status = 'pending'
    ): DealChangeRequest
    {
        return DealChangeRequest::create([
            'deal_id' => $dealId,
            'changes' => $changes,
            'status' => $status,
            'requested_by' => $requestedBy
        ]);
    }

    /**
     * Get filtered deals for index page
     *
     * @param bool $isSuperAdmin
     * @param int|null $userId
     * @param string|null $keyword
     * @param array $selectedStatuses
     * @param array $selectedTypes
     * @param array $selectedPlatforms
     * @param int|null $perPage
     * @return Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredDeals(
        bool    $isSuperAdmin,
        ?int    $userId = null,
        ?string $keyword = null,
        array   $selectedStatuses = [],
        array   $selectedTypes = [],
        array   $selectedPlatforms = [],
        ?string $startDateFrom = null,
        ?string $startDateTo = null,
        ?string $endDateFrom = null,
        ?string $endDateTo = null,
        ?int    $perPage = null
    )
    {
        $query = Deal::query();

        if ($isSuperAdmin) {
            $query->whereNot('status', \Core\Enum\DealStatus::Archived->value);
        } else {
            $query->whereHas('platform', function ($query) use ($userId) {
                $query->where('financial_manager_id', '=', $userId)
                    ->orWhere('owner_id', '=', $userId)
                    ->orWhere('marketing_manager_id', '=', $userId);
            });
        }

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if (!empty($selectedStatuses)) {
            $query->whereIn('status', $selectedStatuses);
        }

        if (!empty($selectedTypes)) {
            $query->whereIn('type', $selectedTypes);
        }

        if (!empty($selectedPlatforms)) {
            $query->whereIn('platform_id', $selectedPlatforms);
        }

        if ($startDateFrom) {
            $query->where('start_date', '>=', $startDateFrom);
        }

        if ($startDateTo) {
            $query->where('start_date', '<=', $startDateTo);
        }

        if ($endDateFrom) {
            $query->where('end_date', '>=', $endDateFrom);
        }

        if ($endDateTo) {
            $query->where('end_date', '<=', $endDateTo);
        }

        $query->with(['platform', 'pendingChangeRequest.requestedBy', 'commissionPlan.iconImage']);

        $query->orderBy('created_at', 'desc')
            ->orderBy('validated', 'asc')
            ->orderBy('platform_id', 'asc');

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Create a new deal
     *
     * @param array $data
     * @return Deal
     */
    public function create(array $data): Deal
    {
        if (!isset($data['plan']) && isset($data['final_commission'])) {
            $data['plan'] = $this->determineNearestPlan($data['final_commission']);
        }

        return Deal::create($data);
    }

    /**
     * Determine the nearest commission plan based on final_commission value
     *
     * @param float $finalCommission
     * @return int|null
     */
    private function determineNearestPlan(float $finalCommission): ?int
    {
        $formula = PlanLabel::where('is_active', true)
            ->selectRaw('*, ABS(final_commission - ?) as commission_diff', [$finalCommission])
            ->orderBy('commission_diff', 'asc')
            ->first();

        return $formula ? $formula->id : null;
    }

    /**
     * Find a deal by ID
     *
     * @param int $id
     * @return Deal|null
     */
    public function find(int $id): ?Deal
    {
        return Deal::find($id);
    }

    /**
     * Update a deal
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        if (!isset($data['plan']) && isset($data['final_commission'])) {
            $data['plan'] = $this->determineNearestPlan($data['final_commission']);
        }

        return Deal::where('id', $id)->update($data);
    }

    /**
     * Get deal parameter from settings
     *
     * @param string $name
     * @return float|int
     */
    public function getDealParameter(string $name)
    {
        $param = DB::table('settings')->where("ParameterName", "=", $name)->first();
        if (!is_null($param)) {
            return $param->DecimalValue;
        }
        return 0;
    }

    /**
     * Get archived deals with optional search filter
     *
     * @param string|null $search
     * @param bool $isSuperAdmin
     * @return Collection
     */
    public function getArchivedDeals(?string $search = null, bool $isSuperAdmin = false): Collection
    {
        $query = Deal::where('status', '=', \Core\Enum\DealStatus::Archived->value);

        if (!is_null($search) && !empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    /**
     * Get dashboard indicators and progress for deals
     *
     * @param int $userId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $platformId
     * @param int|null $dealId
     * @return array
     */
    public function getDashboardIndicators(
        int     $userId,
        ?string $startDate = null,
        ?string $endDate = null,
        ?int    $platformId = null,
        ?int    $dealId = null
    ): array
    {
        $query = Deal::query()
            ->whereHas('platform', function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('marketing_manager_id', $userId)
                        ->orWhere('financial_manager_id', $userId)
                        ->orWhere('owner_id', $userId);
                });
            });

        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        if ($dealId) {
            $query->where('id', $dealId);
        }

        $baseQuery = clone $query;

        $totalDeals = $query->count();

        $pendingRequestDeals = (clone $baseQuery)
            ->whereHas('validationRequests', function ($q) {
                $q->where('status', DealValidationRequest::STATUS_PENDING);
            })
            ->count();

        $validatedDeals = (clone $baseQuery)
            ->where('validated', 1)
            ->count();

        $expiredDeals = (clone $baseQuery)
            ->where('end_date', '<', now())
            ->count();

        $activeDealsCount = (clone $baseQuery)
            ->where('status', \Core\Enum\DealStatus::Opened->value)
            ->count();

        $totalRevenue = (clone $baseQuery)
            ->sum('current_turnover') ?? 0;

        $totalTargetTurnover = (clone $baseQuery)
            ->sum('target_turnover') ?? 0;

        $globalRevenuePercentage = $totalTargetTurnover > 0
            ? round(($totalRevenue / $totalTargetTurnover) * 100, 2)
            : 0;

        $recentDeals = (clone $baseQuery)
            ->with('platform')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'total_deals' => $totalDeals,
            'pending_request_deals' => $pendingRequestDeals,
            'validated_deals' => $validatedDeals,
            'expired_deals' => $expiredDeals,
            'active_deals_count' => $activeDealsCount,
            'total_revenue' => round($totalRevenue, 2),
            'global_revenue_percentage' => $globalRevenuePercentage,
            'recent_deals' => $recentDeals
        ];
    }

    /**
     * Get deal performance chart data over time
     *
     * @param int $userId
     * @param int $dealId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string $viewMode (daily, weekly, monthly)
     * @return array
     */
    public function getDealPerformanceChart(
        int     $userId,
        int     $dealId,
        ?string $startDate = null,
        ?string $endDate = null,
        string  $viewMode = 'daily'
    ): array
    {
        $deal = $this->getPartnerDealById($dealId, $userId);

        if (!$deal) {
            throw new \Exception('Deal not found or access denied');
        }


        $chartData = $this->getRevenueChartData($dealId, $startDate, $endDate, $viewMode);

        $currentRevenue = $this->calculateDealRevenue($dealId, $startDate, $endDate);

        $expectedProgress = $this->calculateExpectedProgress($deal->start_date, $deal->end_date);

        $actualProgress = $deal->target_turnover > 0
            ? round(($currentRevenue / $deal->target_turnover) * 100, 2)
            : 0;


        $commissionPercentage = Deal::getCommissionPercentage($deal, $deal->current_turnover);
        $dealPerformanceScore = Deal::getPerformanceScore($deal);

        return [
            'deal_id' => $deal->id,
            'target_amount' => round($deal->target_turnover, 2),
            'current_revenue' => round($currentRevenue, 2),
            'current_commission' => formatSolde($commissionPercentage, 3),
            'deal_performance_score' => $dealPerformanceScore,
            'expected_progress' => $expectedProgress,
            'actual_progress' => $actualProgress,
            'chart_data' => $chartData
        ];
    }

    /**
     * Get revenue chart data aggregated by view mode
     *
     * @param int $dealId
     * @param string $startDate
     * @param string $endDate
     * @param string $viewMode
     * @return array
     */
    private function getRevenueChartData(
        int     $dealId,
        ?string $startDate,
        ?string $endDate,
        string  $viewMode
    ): array
    {
        $dateFormat = match ($viewMode) {
            'monthly' => '%Y-%m',
            'weekly' => '%Y-%u',
            'daily' => '%Y-%m-%d',
            default => '%Y-%m-%d'
        };

        $displayFormat = match ($viewMode) {
            'monthly' => 'Y-m',
            'weekly' => 'Y-\WW',
            'daily' => 'Y-m-d',
            default => 'Y-m-d'
        };

        $query = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->where('items.deal_id', $dealId);
        if (!is_null($startDate) && !is_null($endDate)) {
            $query = $query->whereBetween('orders.payment_datetime', [$startDate, $endDate]);
        }
        $query = $query->select(
            DB::raw("items.deal_id"),
            DB::raw("DATE_FORMAT(orders.payment_datetime, '$dateFormat') as date_group"),
            DB::raw('SUM(order_details.total_amount) as revenue'),
            DB::raw('items.deal_id as deal_id')
        )
            ->groupBy('date_group')
            ->orderBy('date_group', 'asc');
        $revenueData = $query->get();
        $chartData = [];
        foreach ($revenueData as $data) {
            if ($viewMode === 'weekly') {
                list($year, $week) = explode('-', $data->date_group);
                $date = $year . '-W' . str_pad($week, 2, '0', STR_PAD_LEFT);
            } elseif ($viewMode === 'monthly') {
                $date = $data->date_group . '-01';
            } else {
                $date = $data->date_group;
            }

            $chartData[] = [
                'date' => $date,
                'revenue' => round((float)$data->revenue, 2)
            ];
        }

        return $chartData;
    }

    /**
     * Calculate total revenue for a deal within date range
     *
     * @param int $dealId
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    private function calculateDealRevenue(
        int     $dealId,
        ?string $startDate,
        ?string $endDate
    ): float
    {
        $query = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->where('items.deal_id', $dealId);

        if (!is_null($startDate) && !is_null($endDate)) {
            $query = $query->whereBetween('orders.payment_datetime', [$startDate, $endDate]);
        }

        $query = $query->whereNotNull('orders.payment_datetime');
        $revenue = $query->sum('order_details.total_amount');

        return (float)$revenue ?? 0;
    }

    /**
     * Calculate expected progress based on time elapsed
     *
     * @param string $dealStartDate
     * @param string $dealEndDate
     * @return float
     */
    private function calculateExpectedProgress(
        string $dealStartDate,
        string $dealEndDate
    ): float
    {
        $startDate = \Carbon\Carbon::parse($dealStartDate);
        $endDate = \Carbon\Carbon::parse($dealEndDate);
        $currentDate = \Carbon\Carbon::now();

        // If current date is before start date
        if ($currentDate->lt($startDate)) {
            return 0;
        }

        // If current date is after end date
        if ($currentDate->gt($endDate)) {
            return 100;
        }

        // Calculate percentage of time elapsed
        $totalDuration = $startDate->diffInDays($endDate);
        $elapsedDuration = $startDate->diffInDays($currentDate);

        if ($totalDuration == 0) {
            return 100;
        }

        return round(($elapsedDuration / $totalDuration) * 100, 2);
    }

    public function getAllDeals()
    {
        return Deal::select('id', 'name')->orderBy('name')->get();
    }

    /**
     * Get available deals for a user based on their role
     *
     * @param int $userId
     * @param bool $isSuperAdmin
     * @param int|null $platformId
     * @return Collection
     */
    public function getAvailableDeals(int $userId, bool $isSuperAdmin = false, ?int $platformId = null)
    {
        $query = Deal::query();

        if (!$isSuperAdmin) {
            $query->whereHas('platform', function ($q) use ($userId) {
                $q->where(function ($q2) use ($userId) {
                    $q2->where('financial_manager_id', $userId)
                       ->orWhere('owner_id', $userId)
                       ->orWhere('marketing_manager_id', $userId);
                });
            });
        }

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        return $query->where('status', '!=', 4)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'platform_id', 'status']);
    }
}
