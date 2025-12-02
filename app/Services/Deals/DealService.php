<?php

namespace App\Services\Deals;

use App\Models\Deal;
use App\Models\DealChangeRequest;
use App\Models\DealValidationRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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

        // Apply search filter
        if (!is_null($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('platform', function ($platformQuery) use ($search) {
                        $platformQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by user permissions through platform
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

        // Return paginated or all results
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

        // Apply search filter
        if (!is_null($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('platform', function ($platformQuery) use ($search) {
                        $platformQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by user permissions through platform
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
            $deal->change_requests_count = DealChangeRequest::where('deal_id', $deal->id)->count();
            $deal->changeRequests = DealChangeRequest::where('deal_id', $deal->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            $deal->validation_requests_count = DealValidationRequest::where('deal_id', $deal->id)->count();
            $deal->validationRequests = DealValidationRequest::where('deal_id', $deal->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        });
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

        $query->with(['platform', 'pendingChangeRequest.requestedBy']);

        $query->orderBy('validated', 'ASC')->orderBy('platform_id', 'ASC');

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
        return Deal::create($data);
    }
}

