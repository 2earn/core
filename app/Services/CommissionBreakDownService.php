<?php

namespace App\Services;

use App\Models\CommissionBreakDown;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class CommissionBreakDownService
{
    /**
     * Get commission breakdowns for a deal
     *
     * @param int $dealId
     * @param string $orderBy
     * @param string $orderDirection
     * @return Collection
     */
    public function getByDealId(int $dealId, string $orderBy = 'id', string $orderDirection = 'ASC'): Collection
    {
        try {
            return CommissionBreakDown::where('deal_id', $dealId)
                ->orderBy($orderBy, $orderDirection)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching commission breakdowns for deal: ' . $e->getMessage(), ['deal_id' => $dealId]);
            return new Collection();
        }
    }

    /**
     * Get a commission breakdown by ID
     *
     * @param int $id
     * @return CommissionBreakDown|null
     */
    public function getById(int $id): ?CommissionBreakDown
    {
        try {
            return CommissionBreakDown::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching commission breakdown by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }

    /**
     * Calculate commission totals for a deal
     *
     * @param int $dealId
     * @return array
     */
    public function calculateTotals(int $dealId): array
    {
        try {
            $commissions = $this->getByDealId($dealId);

            return [
                'jackpot' => $commissions->sum('cash_jackpot'),
                'earn_profit' => $commissions->sum('cash_company_profit'),
                'proactive_cashback' => $commissions->sum('cash_cashback'),
                'tree_remuneration' => $commissions->sum('cash_tree'),
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating commission totals: ' . $e->getMessage(), ['deal_id' => $dealId]);
            return [
                'jackpot' => 0,
                'earn_profit' => 0,
                'proactive_cashback' => 0,
                'tree_remuneration' => 0,
            ];
        }
    }

    /**
     * Create a commission breakdown
     *
     * @param array $data
     * @return CommissionBreakDown|null
     */
    public function create(array $data): ?CommissionBreakDown
    {
        try {
            return CommissionBreakDown::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating commission breakdown: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Update a commission breakdown
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $commission = CommissionBreakDown::findOrFail($id);
            return $commission->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating commission breakdown: ' . $e->getMessage(), ['id' => $id, 'data' => $data]);
            return false;
        }
    }

    /**
     * Delete a commission breakdown
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $commission = CommissionBreakDown::findOrFail($id);
            return $commission->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting commission breakdown: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }
}

