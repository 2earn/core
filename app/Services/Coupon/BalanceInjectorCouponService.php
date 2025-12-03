<?php

namespace App\Services\Coupon;

use App\Models\BalanceInjectorCoupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BalanceInjectorCouponService
{
    /**
     * Get paginated coupons with search and sorting
     *
     * @param string|null $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(
        ?string $search = null,
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        try {
            $query = BalanceInjectorCoupon::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pin', 'like', '%' . $search . '%')
                        ->orWhere('sn', 'like', '%' . $search . '%')
                        ->orWhere('value', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                });
            }

            return $query->orderBy($sortField, $sortDirection)->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated coupons: ' . $e->getMessage(), [
                'search' => $search,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection
            ]);
            throw $e;
        }
    }

    /**
     * Get a coupon by ID
     *
     * @param int $id
     * @return BalanceInjectorCoupon|null
     */
    public function getById(int $id): ?BalanceInjectorCoupon
    {
        try {
            return BalanceInjectorCoupon::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching coupon by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }

    /**
     * Get a coupon by ID or fail
     *
     * @param int $id
     * @return BalanceInjectorCoupon
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): BalanceInjectorCoupon
    {
        return BalanceInjectorCoupon::findOrFail($id);
    }

    /**
     * Delete a coupon
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $coupon = BalanceInjectorCoupon::findOrFail($id);
            return $coupon->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage(), ['id' => $id]);
            throw $e;
        }
    }

    /**
     * Delete multiple coupons (only unconsumed ones)
     *
     * @param array $ids
     * @return int Number of deleted coupons
     */
    public function deleteMultiple(array $ids): int
    {
        try {
            return BalanceInjectorCoupon::whereIn('id', $ids)
                ->where('consumed', 0)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting multiple coupons: ' . $e->getMessage(), [
                'ids' => $ids
            ]);
            throw $e;
        }
    }

    /**
     * Get all coupons
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return BalanceInjectorCoupon::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all coupons: ' . $e->getMessage());
            return new Collection();
        }
    }
}

