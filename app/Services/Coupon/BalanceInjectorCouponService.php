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

    /**
     * Get a coupon by PIN code
     *
     * @param string $pin
     * @return BalanceInjectorCoupon|null
     */
    public function getByPin(string $pin): ?BalanceInjectorCoupon
    {
        try {
            return BalanceInjectorCoupon::where('pin', $pin)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching coupon by PIN: ' . $e->getMessage(), ['pin' => $pin]);
            return null;
        }
    }

    /**
     * Get coupons by user ID ordered by created_at descending
     *
     * @param int $userId
     * @return Collection
     */
    public function getByUserId(int $userId): Collection
    {
        try {
            return BalanceInjectorCoupon::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching coupons by user ID: ' . $e->getMessage(), ['user_id' => $userId]);
            return new Collection();
        }
    }

    /**
     * Create multiple balance injector coupons
     *
     * @param int $numberOfCoupons Number of coupons to create
     * @param array $couponData Base coupon data (attachment_date, value, category, consumed)
     * @param string|null $type Coupon type
     * @return array Result array with success status, message, and created count
     */
    public function createMultipleCoupons(int $numberOfCoupons, array $couponData, ?string $type = null): array
    {
        try {
            // Validate number of coupons
            if (!is_numeric($numberOfCoupons) || $numberOfCoupons <= 0 || $numberOfCoupons >= 100) {
                return [
                    'success' => false,
                    'message' => 'Number of coupons must be a positive number less than 100'
                ];
            }

            $dateNow = now()->format('YmdHis');
            $createdCount = 0;

            // Determine type based on category
            $finalType = ($couponData['category'] == 2 && empty($type)) ? '100.00' : $type;

            for ($i = 1; $i <= $numberOfCoupons; $i++) {
                $coupon = $couponData;
                $coupon['pin'] = $dateNow . strtoupper(\Illuminate\Support\Str::random(10));
                $coupon['sn'] = 'SN' . $dateNow . rand(100000, 999999);
                $coupon['type'] = $finalType;

                BalanceInjectorCoupon::create($coupon);
                $createdCount++;
            }

            return [
                'success' => true,
                'message' => 'Coupons created Successfully',
                'createdCount' => $createdCount
            ];

        } catch (\Exception $exception) {
            Log::error('Error creating multiple coupons: ' . $exception->getMessage(), [
                'numberOfCoupons' => $numberOfCoupons,
                'couponData' => $couponData
            ]);

            return [
                'success' => false,
                'message' => 'Coupons creation Failed: ' . $exception->getMessage()
            ];
        }
    }
}

