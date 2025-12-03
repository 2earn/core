<?php

namespace App\Services\Coupon;

use App\Models\BalanceInjectorCoupon;
use App\Models\Coupon;
use Core\Enum\CouponStatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Get a Coupon by ID
     *
     * @param int $id
     * @return BalanceInjectorCoupon|null
     */
    public function getById(int $id): ?BalanceInjectorCoupon
    {
        return BalanceInjectorCoupon::find($id);
    }

    /**
     * Get a Coupon by ID or fail
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
     * Get paginated user coupons with search
     *
     * @param int $userId
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserCouponsPaginated(int $userId, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = BalanceInjectorCoupon::where('user_id', $userId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('pin', 'like', '%' . $search . '%')
                  ->orWhere('sn', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all user coupons
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCoupons(int $userId): Collection
    {
        return BalanceInjectorCoupon::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Delete multiple coupons (only unconsumed ones)
     *
     * @param array $ids
     * @param int $userId
     * @return int Number of deleted coupons
     */
    public function deleteMultiple(array $ids, int $userId): int
    {
        return BalanceInjectorCoupon::whereIn('id', $ids)
            ->where('user_id', $userId)
            ->where('consumed', 0)
            ->delete();
    }

    /**
     * Delete a single coupon
     *
     * @param int $id
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id, int $userId): bool
    {
        $coupon = BalanceInjectorCoupon::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($coupon->consumed) {
            throw new \Exception('Cannot delete consumed coupon');
        }

        return $coupon->delete();
    }

    /**
     * Consume a coupon
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function consume(int $id, int $userId): bool
    {
        $coupon = BalanceInjectorCoupon::findOrFail($id);
        return BalanceInjectorCoupon::consume($coupon);
    }

    /**
     * Get paginated coupons with search and sorting (admin view)
     *
     * @param string|null $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCouponsPaginated(
        ?string $search = null,
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        try {
            $query = BalanceInjectorCoupon::query();

            if ($search) {
                $query->where(function($q) use ($search) {
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
     * Delete a coupon by ID (admin delete)
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteById(int $id): bool
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
     * Delete multiple coupons by IDs (only unconsumed ones)
     *
     * @param array $ids
     * @return int Number of deleted coupons
     */
    public function deleteMultipleByIds(array $ids): int
    {
        try {
            return BalanceInjectorCoupon::whereIn('id', $ids)
                ->where('consumed', 0)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting multiple coupons: ' . $e->getMessage(), ['ids' => $ids]);
            throw $e;
        }
    }

    /**
     * Get purchased coupons for a user with search and pagination
     *
     * @param int $userId
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPurchasedCouponsPaginated(
        int $userId,
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        try {
            $query = Coupon::where('user_id', $userId)
                ->where('status', CouponStatusEnum::purchased->value);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pin', 'like', '%' . $search . '%')
                        ->orWhere('sn', 'like', '%' . $search . '%')
                        ->orWhere('value', 'like', '%' . $search . '%')
                        ->orWhereHas('platform', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            }

            return $query->orderBy('id', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching purchased coupons: ' . $e->getMessage(), [
                'user_id' => $userId,
                'search' => $search
            ]);
            throw $e;
        }
    }

    /**
     * Mark a coupon as consumed
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function markAsConsumed(int $id): bool
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return $coupon->update([
                'consumed' => 1,
                'consumption_date' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking coupon as consumed: ' . $e->getMessage(), ['id' => $id]);
            throw $e;
        }
    }

    /**
     * Get a coupon by serial number
     *
     * @param string $sn
     * @return Coupon|null
     */
    public function getBySn(string $sn): ?Coupon
    {
        try {
            return Coupon::where('sn', $sn)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching coupon by SN: ' . $e->getMessage(), ['sn' => $sn]);
            return null;
        }
    }
}

