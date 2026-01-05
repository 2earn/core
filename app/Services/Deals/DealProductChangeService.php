<?php

namespace App\Services\Deals;

use App\Models\DealProductChange;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DealProductChangeService
{
    public function getFilteredChanges(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = DealProductChange::with(['deal', 'item', 'changedBy']);

        if (isset($filters['deal_id'])) {
            $query->where('deal_id', $filters['deal_id']);
        }

        if (isset($filters['item_id'])) {
            $query->where('item_id', $filters['item_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['changed_by'])) {
            $query->where('changed_by', $filters['changed_by']);
        }

        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getChangeById(int $id): ?DealProductChange
    {
        return DealProductChange::with(['deal', 'item', 'changedBy'])->find($id);
    }

    public function getStatistics(array $filters): array
    {
        $query = DealProductChange::query();

        if (isset($filters['deal_id'])) {
            $query->where('deal_id', $filters['deal_id']);
        }

        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }

        $totalChanges = $query->count();
        $addedCount = (clone $query)->where('action', 'added')->count();
        $removedCount = (clone $query)->where('action', 'removed')->count();

        $recentChanges = (clone $query)
            ->with(['deal', 'item', 'changedBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $topUsers = (clone $query)
            ->select('changed_by', DB::raw('COUNT(*) as changes_count'))
            ->whereNotNull('changed_by')
            ->groupBy('changed_by')
            ->orderBy('changes_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->load('changedBy');
                return $item;
            });

        return [
            'total_changes' => $totalChanges,
            'added_count' => $addedCount,
            'removed_count' => $removedCount,
            'recent_changes' => $recentChanges,
            'top_users' => $topUsers
        ];
    }

    public function createChange(array $data): DealProductChange
    {
        return DealProductChange::create($data);
    }

    public function createBulkChanges(int $dealId, array $itemIds, string $action, ?int $changedBy = null, ?string $note = null): int
    {
        $changes = [];
        $timestamp = now();

        foreach ($itemIds as $itemId) {
            $changes[] = [
                'deal_id' => $dealId,
                'item_id' => $itemId,
                'action' => $action,
                'changed_by' => $changedBy,
                'note' => $note,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        return DealProductChange::insert($changes) ? count($changes) : 0;
    }
}

