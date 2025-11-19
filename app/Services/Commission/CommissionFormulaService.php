<?php

namespace App\Services\Commission;

use App\Models\CommissionFormula;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

class CommissionFormulaService
{
    /**
     * Get all commission formulas with optional filters
     *
     * @param array $filters
     * @return EloquentCollection
     */
    public function getCommissionFormulas(array $filters = []): EloquentCollection
    {
        try {
            $query = CommissionFormula::query();

            // Apply active filter - ensure proper boolean handling
            if (array_key_exists('is_active', $filters)) {
                // Cast to boolean to ensure consistency
                $query->where('is_active', (bool) $filters['is_active']);
            }

            // Apply search filter
            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            // Apply range filter
            if (isset($filters['min_commission']) && isset($filters['max_commission'])) {
                $query->withinRange($filters['min_commission'], $filters['max_commission']);
            }

            // Load relationships if specified
            if (!empty($filters['with'])) {
                $query->with($filters['with']);
            }

            // Apply ordering
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            return $query->get();
        } catch (\Exception $e) {
            Log::error('Error fetching commission formulas: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get only active commission formulas
     *
     * @return EloquentCollection
     */
    public function getActiveFormulas(): EloquentCollection
    {
        try {
            return CommissionFormula::active()
                ->orderBy('initial_commission')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching active commission formulas: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get commission formula by ID
     *
     * @param int $id
     * @return CommissionFormula|null
     */
    public function getCommissionFormulaById(int $id): ?CommissionFormula
    {
        try {
            return CommissionFormula::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching commission formula by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new commission formula
     *
     * @param array $data
     * @return CommissionFormula|null
     */
    public function createCommissionFormula(array $data): ?CommissionFormula
    {
        try {
            return CommissionFormula::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating commission formula: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update commission formula data
     *
     * @param int $id
     * @param array $data
     * @return CommissionFormula|null
     */
    public function updateCommissionFormula(int $id, array $data): ?CommissionFormula
    {
        try {
            $formula = CommissionFormula::findOrFail($id);
            $formula->update($data);
            return $formula->fresh();
        } catch (\Exception $e) {
            Log::error('Error updating commission formula: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete commission formula (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function deleteCommissionFormula(int $id): bool
    {
        try {
            $formula = CommissionFormula::findOrFail($id);
            return $formula->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting commission formula: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle active status
     *
     * @param int $id
     * @return bool
     */
    public function toggleActive(int $id): bool
    {
        try {
            $formula = CommissionFormula::findOrFail($id);
            $formula->is_active = !$formula->is_active;
            return $formula->save();
        } catch (\Exception $e) {
            Log::error('Error toggling commission formula status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate commission for a value
     *
     * @param int $formulaId
     * @param float $value
     * @param string $type 'initial' or 'final'
     * @return float|null
     */
    public function calculateCommission(int $formulaId, float $value, string $type = 'initial'): ?float
    {
        try {
            $formula = CommissionFormula::find($formulaId);

            if (!$formula) {
                return null;
            }

            return $formula->calculateCommission($value, $type);
        } catch (\Exception $e) {
            Log::error('Error calculating commission: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get formulas for dropdown/select
     *
     * @return EloquentCollection
     */
    public function getForSelect(): EloquentCollection
    {
        try {
            return CommissionFormula::active()
                ->select('id', 'name', 'initial_commission', 'final_commission')
                ->orderBy('initial_commission')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching commission formulas for select: ' . $e->getMessage());
            return new EloquentCollection();
        }
    }

    /**
     * Get paginated commission formulas for API with optional filters
     *
     * @param array $filters
     * @param int|null $page
     * @param int $perPage
     * @return array
     */
    public function getPaginatedFormulas(array $filters = [], ?int $page = null, int $perPage = 10): array
    {
        try {
            $query = CommissionFormula::query();

            // Apply active filter - ensure proper boolean handling
            if (array_key_exists('is_active', $filters)) {
                // Cast to boolean to ensure consistency
                $query->where('is_active', (bool) $filters['is_active']);
            }

            // Apply search filter
            if (!empty($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            }

            // Apply ordering
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            $total = $query->count();

            // Get results with or without pagination
            $formulas = $page ? $query->paginate($perPage, ['*'], 'page', $page) : $query->get();

            // Append commission_range to each formula
            $formulas->each(function ($formula) {
                $formula->commission_range = $formula->getCommissionRange();
            });
            return [
                'formulas' => $formulas,
                'total' => $total
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching paginated commission formulas: ' . $e->getMessage());
            return [
                'formulas' => new EloquentCollection(),
                'total' => 0
            ];
        }
    }

    /**
     * Get commission formula statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            return [
                'total' => CommissionFormula::count(),
                'active' => CommissionFormula::active()->count(),
                'inactive' => CommissionFormula::where('is_active', false)->count(),
                'avg_initial' => CommissionFormula::avg('initial_commission'),
                'avg_final' => CommissionFormula::avg('final_commission'),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting commission formula statistics: ' . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'avg_initial' => 0,
                'avg_final' => 0,
            ];
        }
    }

    /**
     * Find formula by commission range
     *
     * @param float $initialCommission
     * @param float $finalCommission
     * @return CommissionFormula|null
     */
    public function findByRange(float $initialCommission, float $finalCommission): ?CommissionFormula
    {
        try {
            return CommissionFormula::where('initial_commission', $initialCommission)
                ->where('final_commission', $finalCommission)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error finding commission formula by range: ' . $e->getMessage());
            return null;
        }
    }
}

