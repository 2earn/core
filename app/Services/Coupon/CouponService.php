<?php

namespace App\Services\Coupon;

use App\Models\BalanceInjectorCoupon;
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
}

