<?php

namespace App\Services\Coupon;

use App\Enums\CouponStatusEnum;
use App\Models\BalanceInjectorCoupon;
use App\Models\Coupon;
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
            $query->where(function ($q) use ($search) {
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
        string  $sortField = 'created_at',
        string  $sortDirection = 'desc',
        int     $perPage = 10
    ): LengthAwarePaginator
    {
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
    public function getMaxAvailableAmount($idPlatform): float
    {
        return Coupon::where(function ($query) {

            $query
                ->orWhere('status', CouponStatusEnum::available->value)
                ->orWhere(function ($subQueryReservedForOther) {
                    $subQueryReservedForOther->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '<', now());
                })
                ->orWhere(function ($subQueryReservedForUser) {
                    $subQueryReservedForUser->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '>=', now())
                        ->where('user_id', auth()->user()->id);
                });
        })
            ->where('platform_id', $idPlatform)
            ->sum('value');
    }

    public function deleteMultipleByIds(array $ids): int
    {
        try {
            return Coupon::whereIn('id', $ids)
                ->where('consumed', 0)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting multiple coupons: ' . $e->getMessage(), ['ids' => $ids]);
            throw $e;
        }
    }

    /**
     * Get all coupons ordered by ID descending
     *
     * @return Collection
     */
    public function getAllCouponsOrdered(): Collection
    {
        try {
            return Coupon::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all ordered coupons: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get paginated purchased coupons for a user with search
     *
     * @param int $userId
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPurchasedCouponsPaginated(int $userId, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Coupon::with('platform')
            ->where('user_id', $userId)
            ->whereNotNull('purchase_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pin', 'like', '%' . $search . '%')
                    ->orWhere('sn', 'like', '%' . $search . '%')
                    ->orWhere('value', 'like', '%' . $search . '%')
                    ->orWhereHas('platform', function ($platformQuery) use ($search) {
                        $platformQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query->orderBy('purchase_date', 'desc')->paginate($perPage);
    }

    /**
     * Get purchased coupons for a user by status
     *
     * @param int $userId
     * @param int $status
     * @return Collection
     */
    public function getPurchasedCouponsByStatus(int $userId, int $status): Collection
    {
        try {
            return Coupon::where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching purchased coupons by status: ' . $e->getMessage(), [
                'user_id' => $userId,
                'status' => $status
            ]);
            return new Collection();
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

            if ($coupon->consumed) {
                throw new \Exception('Coupon is already consumed');
            }

            $coupon->consumed = true;
            $coupon->consumption_date = now();
            $coupon->status = CouponStatusEnum::consumed->value;

            return $coupon->save();
        } catch (\Exception $e) {
            Log::error('Error marking coupon as consumed: ' . $e->getMessage(), ['id' => $id]);
            throw $e;
        }
    }

    /**
     * Get a coupon by serial number (SN)
     *
     * @param string $sn
     * @return Coupon
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getBySn(string $sn): Coupon
    {
        return Coupon::where('sn', $sn)->firstOrFail();
    }

    /**
     * Find a coupon by ID
     *
     * @param int $id
     * @return Coupon|null
     */
    public function findCouponById(int $id): ?Coupon
    {
        return Coupon::find($id);
    }

    /**
     * Update a coupon
     *
     * @param Coupon $coupon
     * @param array $data
     * @return bool
     */
    public function updateCoupon(Coupon $coupon, array $data): bool
    {
        return $coupon->update($data);
    }

    /**
     * Get available coupons for a platform
     *
     * @param int $platformId
     * @param int $userId
     * @return Collection
     */
    public function getAvailableCouponsForPlatform(int $platformId, int $userId): Collection
    {
        return Coupon::where(function ($query) use ($userId) {
            $query
                ->orWhere('status', CouponStatusEnum::available->value)
                ->orWhere(function ($subQueryReservedForOther) {
                    $subQueryReservedForOther->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '<', now());
                })
                ->orWhere(function ($subQueryReservedForUser) use ($userId) {
                    $subQueryReservedForUser->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '>=', now())
                        ->where('user_id', $userId);
                });
        })
            ->where('platform_id', $platformId)
            ->orderBy('value', 'desc')
            ->get();
    }

    /**
     * Create multiple coupons (bulk creation)
     *
     * @param array $pins Array of PIN codes
     * @param array $sns Array of serial numbers
     * @param array $couponData Base coupon data (attachment_date, value, platform_id, consumed)
     * @return int Number of coupons created
     * @throws \Exception
     */
    public function createMultipleCoupons(array $pins, array $sns, array $couponData): int
    {
        try {
            $createdCount = 0;

            foreach ($pins as $key => $pin) {
                $couponData['pin'] = $pin;
                $couponData['sn'] = $sns[$key];
                Coupon::create($couponData);
                $createdCount++;
            }

            return $createdCount;
        } catch (\Exception $e) {
            Log::error('Error creating multiple coupons: ' . $e->getMessage(), [
                'pins_count' => count($pins),
                'sns_count' => count($sns)
            ]);
            throw $e;
        }
    }

    /**
     * Process coupon purchase with order creation and execution
     *
     * @param array $coupons Array of coupons to purchase
     * @param int $userId User ID making the purchase
     * @param int $platformId Platform ID
     * @param string $platformName Platform name
     * @param int $itemId Item ID for order details
     * @return array Result array with success status, message, order, and coupons
     */
    public function buyCoupon(
        array $coupons,
        int $userId,
        int $platformId,
        string $platformName,
        int $itemId
    ): array
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Create order
            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'platform_id' => $platformId,
                'note' => 'Coupons buy from' . ' :' . $platformId . '-' . $platformName
            ]);

            // Calculate totals and prepare note
            $total_amount = $unit_price = 0;
            $serialNumbers = [];
            foreach ($coupons as $couponItem) {
                $unit_price += $couponItem['value'];
                $total_amount += $couponItem['value'];
                $serialNumbers[] = $couponItem['sn'];
            }

            // Create order details
            $order->orderDetails()->create([
                'qty' => 1,
                'unit_price' => $unit_price,
                'total_amount' => $total_amount,
                'note' => implode(",", $serialNumbers),
                'item_id' => $itemId,
            ]);

            // Update order status and simulate
            $order->updateStatus(\App\Enums\OrderEnum::Ready);
            $simulation = \App\Services\Orders\Ordering::simulate($order);

            if (!$simulation) {
                $order->updateStatus(\App\Enums\OrderEnum::Failed);
                \Illuminate\Support\Facades\DB::commit();
                return [
                    'success' => false,
                    'message' => 'Coupons order failed'
                ];
            }

            // Run the order
            $status = \App\Services\Orders\Ordering::run($simulation);
            if ($status->value == \App\Enums\OrderEnum::Failed->value) {
                \Illuminate\Support\Facades\DB::commit();
                return [
                    'success' => false,
                    'message' => 'Coupons order failed'
                ];
            }

            // Update coupons as purchased
            $purchasedCoupons = [];
            foreach ($serialNumbers as $sn) {
                $coupon = $this->getBySn($sn);
                if ($coupon && !$coupon->consumed) {
                    $this->updateCoupon($coupon, [
                        'user_id' => $userId,
                        'purchase_date' => now(),
                        'status' => CouponStatusEnum::purchased->value
                    ]);
                }
                $purchasedCoupons[] = $coupon;
            }

            \Illuminate\Support\Facades\DB::commit();

            return [
                'success' => true,
                'message' => 'Coupons purchased successfully',
                'order' => $order,
                'coupons' => $purchasedCoupons,
                'totalAmount' => $total_amount
            ];

        } catch (\Exception $exception) {
            \Illuminate\Support\Facades\DB::rollBack();
            Log::error('Error processing coupon purchase: ' . $exception->getMessage());

            if (isset($order)) {
                $order->updateStatus(\App\Enums\OrderEnum::Failed);
            }

            return [
                'success' => false,
                'message' => 'Error processing coupon purchase'
            ];
        }
    }

    /**
     * Get coupons for specified amount with reservation
     *
     * @param int $platformId Platform ID
     * @param int $userId User ID
     * @param float $amount Target amount
     * @param int $reservationMinutes Minutes to reserve coupons
     * @param bool $isEqual Whether the amount should match exactly
     * @return array Result array with amount, coupons, and lastValue
     */
    public function getCouponsForAmount(
        int $platformId,
        int $userId,
        float $amount,
        int $reservationMinutes,
        bool $isEqual = false
    ): array
    {
        try {
            $availableCoupons = $this->getAvailableCouponsForPlatform($platformId, $userId);

            $selectedCoupons = [];
            $total = 0;
            $lastValue = 0;

            if ($availableCoupons->count() == 0) {
                $lastValue = 0;
            }

            foreach ($availableCoupons as $coupon) {
                $lastValue = $coupon->value;
                if ($total + $coupon->value <= $amount) {
                    $this->updateCoupon($coupon, [
                        'status' => CouponStatusEnum::reserved->value,
                        'user_id' => $userId,
                        'reserved_until' => now()->addMinutes($reservationMinutes)
                    ]);
                    $selectedCoupons[] = $coupon;
                    $total += $coupon->value;
                }
            }

            return [
                'amount' => $total,
                'coupons' => $selectedCoupons,
                'lastValue' => $isEqual ? 0 : $lastValue,
            ];

        } catch (\Exception $exception) {
            Log::error('Error getting coupons for amount: ' . $exception->getMessage());
            return [
                'amount' => 0,
                'coupons' => [],
                'lastValue' => 0,
            ];
        }
    }

    /**
     * Simulate coupon purchase for a given amount
     *
     * @param int $platformId Platform ID
     * @param int $userId User ID
     * @param float $displayedAmount Amount to simulate
     * @param int $reservationMinutes Minutes to reserve coupons
     * @return array Result array with success status, simulation results, and UI data
     */
    public function simulateCouponPurchase(
        int $platformId,
        int $userId,
        float $displayedAmount,
        int $reservationMinutes
    ): array
    {
        try {
            // Validate amount
            if (empty($displayedAmount) || $displayedAmount == "0" || intval($displayedAmount) < 1) {
                return [
                    'success' => false,
                    'message' => 'Wrong wintered amount'
                ];
            }

            // Get coupons for the requested amount
            $preSimulationResult = $this->getCouponsForAmount(
                $platformId,
                $userId,
                $displayedAmount,
                $reservationMinutes,
                false
            );

            // Check if simulation failed
            if (is_null($preSimulationResult)) {
                return [
                    'success' => false,
                    'message' => 'Amount simulation failed'
                ];
            }

            // Check if amount matches exactly
            $isEqual = ($preSimulationResult['amount'] == $displayedAmount);

            // Get alternative simulation with next coupon value added
            $alternativeResult = $this->getCouponsForAmount(
                $platformId,
                $userId,
                $preSimulationResult['lastValue'] + $displayedAmount,
                $reservationMinutes,
                true
            );

            // Determine final coupons and amount based on exact match
            $finalCoupons = $isEqual ? $preSimulationResult['coupons'] : $alternativeResult['coupons'];
            $finalAmount = $preSimulationResult['amount'];
            $lastValue = $preSimulationResult['lastValue'];

            return [
                'success' => true,
                'equal' => $isEqual,
                'amount' => $finalAmount,
                'lastValue' => $lastValue,
                'coupons' => $finalCoupons,
                'preSimulationResult' => $preSimulationResult,
                'alternativeResult' => $alternativeResult,
                'displayedAmount' => $displayedAmount
            ];

        } catch (\Exception $exception) {
            Log::error('Error simulating coupon purchase: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error simulating coupon purchase'
            ];
        }
    }
}

