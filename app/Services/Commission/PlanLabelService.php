<?php

namespace App\Services\Commission;

use App\Models\PlanLabel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

class PlanLabelService
{
    public function getPlanLabels(array $filters = []): EloquentCollection
    {
        try {
            $query = PlanLabel::query();

            if (array_key_exists('is_active', $filters)) {
                $query->where('is_active', (bool) $filters['is_active']);
            }

            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (isset($filters['stars'])) {
                $query->byStars($filters['stars']);
            }

            if (isset($filters['step'])) {
                $query->where('step', $filters['step']);
            }

            if (isset($filters['min_commission']) && isset($filters['max_commission'])) {
                $query->withinRange($filters['min_commission'], $filters['max_commission']);
            }

            if (!empty($filters['with'])) {
                $query->with($filters['with']);
            }

            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching plan labels: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    public function getActiveLabels(): EloquentCollection
    {
        try {
            return PlanLabel::active()
                ->orderBy('initial_commission')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching active plan labels: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    public function getPlanLabelById(int $id): ?PlanLabel
    {
        try {
            return PlanLabel::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching plan label by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function createPlanLabel(array $data): ?PlanLabel
    {
        try {
            return PlanLabel::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating plan label: ' . $e->getMessage());
            return null;
        }
    }

    public function updatePlanLabel(int $id, array $data): ?PlanLabel
    {
        try {
            $label = PlanLabel::findOrFail($id);
            $label->update($data);
            return $label->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating plan label: ' . $e->getMessage());
            return null;
        }
    }

    public function deletePlanLabel(int $id): bool
    {
        try {
            $label = PlanLabel::findOrFail($id);
            return $label->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting plan label: ' . $e->getMessage());
            return false;
        }
    }

    public function toggleActive(int $id): bool
    {
        try {
            $label = PlanLabel::findOrFail($id);
            $label->is_active = !$label->is_active;
            return $label->save();
        } catch (\Exception $e) {
            Log::error('Error toggling plan label status: ' . $e->getMessage());
            return false;
        }
    }

    public function calculateCommission(int $labelId, float $value, string $type = 'initial'): ?float
    {
        try {
            $label = PlanLabel::find($labelId);

            if (!$label) {
                return null;
            }

            return $label->calculateCommission($value, $type);
        } catch (\Exception $e) {
            Log::error('Error calculating commission: ' . $e->getMessage());
            return null;
        }
    }

    public function getForSelect(): EloquentCollection
    {
        try {
            return PlanLabel::active()
                ->select('id', 'name', 'initial_commission', 'final_commission', 'step', 'rate', 'stars')
                ->orderBy('initial_commission')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching plan labels for select: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    public function getPaginatedLabels(array $filters = [], ?int $page = null, int $perPage = 10): array
    {
        try {
            $query = PlanLabel::query();

            if (array_key_exists('is_active', $filters)) {
                $query->where('is_active', (bool) $filters['is_active']);
            }

            if (!empty($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            }

            if (isset($filters['stars'])) {
                $query->byStars($filters['stars']);
            }

            if (isset($filters['step'])) {
                $query->where('step', $filters['step']);
            }

            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            $total = $query->count();

            $labels = $page ? $query->paginate($perPage, ['*'], 'page', $page) : $query->get();

            $labels->each(function ($label) {
                $label->commission_range = $label->getCommissionRange();
            });

            return [
                'labels' => $labels,
                'total' => $total
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching paginated plan labels: ' . $e->getMessage());
            return [
                'labels' => new EloquentCollection(),
                'total' => 0
            ];
        }
    }

    public function getStatistics(): array
    {
        try {
            return [
                'total' => PlanLabel::count(),
                'active' => PlanLabel::active()->count(),
                'inactive' => PlanLabel::where('is_active', false)->count(),
                'avg_initial' => PlanLabel::avg('initial_commission'),
                'avg_final' => PlanLabel::avg('final_commission'),
                'avg_rate' => PlanLabel::avg('rate'),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting plan label statistics: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'avg_initial' => 0,
                'avg_final' => 0,
                'avg_rate' => 0,
            ];
        }
    }

    public function findByRange(float $initialCommission, float $finalCommission): ?PlanLabel
    {
        try {
            return PlanLabel::where('initial_commission', $initialCommission)
                ->where('final_commission', $finalCommission)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding plan label by range: ' . $e->getMessage());
            return null;
        }
    }

    public function getLabelsByStars(int $stars): EloquentCollection
    {
        try {
            return PlanLabel::byStars($stars)
                ->active()
                ->orderBy('step')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching plan labels by stars: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }
}

