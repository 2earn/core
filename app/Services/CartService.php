<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class CartService
{
    private const LOG_PREFIX = '[CartService] ';

    /**
     * Get cart for a specific user
     *
     * @param int $userId
     * @return Cart|null
     */
    public function getUserCart(int $userId): ?Cart
    {
        try {
            return Cart::where('user_id', $userId)->first();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error getting user cart: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Check if cart is empty for a user
     *
     * @param int $userId
     * @return bool
     */
    public function isCartEmpty(int $userId): bool
    {
        try {
            $cart = $this->getUserCart($userId);
            return !$cart || $cart->cartItem()->count() == 0;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error checking if cart is empty: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Get cart items grouped by platform
     *
     * @param int $userId
     * @return array
     */
    public function getCartItemsGroupedByPlatform(int $userId): array
    {
        try {
            $cart = $this->getUserCart($userId);
            if (!$cart) {
                return [];
            }

            $ordersData = [];

            foreach ($cart->cartItem()->get() as $cartItem) {
                $item = $cartItem->item()->first();
                if ($item && $item->deal()->first()) {
                    $platformId = $item->deal()->first()->platform_id;
                } else {
                    $platformId = $item->platform_id ?? null;
                }

                if ($platformId) {
                    $ordersData[$platformId][] = $cartItem;
                }
            }

            return $ordersData;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error grouping cart items by platform: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Get count of unique platforms in cart
     *
     * @param int $userId
     * @return int
     */
    public function getUniquePlatformsCount(int $userId): int
    {
        try {
            $cart = $this->getUserCart($userId);
            if (!$cart) {
                return 0;
            }

            $platformIds = [];
            foreach ($cart->cartItem()->get() as $cartItem) {
                $item = $cartItem->item()->first();
                if ($item && $item->deal()->first()) {
                    $platformId = $item->deal()->first()->platform_id;
                } else {
                    $platformId = $item->platform_id ?? null;
                }

                if ($platformId && !in_array($platformId, $platformIds)) {
                    $platformIds[] = $platformId;
                }
            }

            return count($platformIds);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error counting unique platforms: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }
}

