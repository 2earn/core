<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
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

    /**
     * Get or create cart for a specific user
     *
     * @param int $userId
     * @return Cart
     */
    public function getOrCreateCart(int $userId): Cart
    {
        try {
            $cart = $this->getUserCart($userId);
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'total_cart' => 0,
                    'total_cart_quantity' => 0,
                    'shipping' => 0,
                ]);
            }
            return $cart;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error getting or creating cart: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Add item to cart
     *
     * @param int $userId
     * @param int $itemId
     * @param int $qty
     * @return void
     */
    public function addItemToCart(int $userId, int $itemId, int $qty = 1): void
    {
        try {
            $cart = $this->getOrCreateCart($userId);
            $item = \App\Models\Item::findOrFail($itemId);

            $existingCartItem = $cart->cartItem()->where('item_id', $itemId)->first();

            if ($existingCartItem) {
                $existingCartItem->qty += $qty;
                $existingCartItem->total_amount = $existingCartItem->qty * $existingCartItem->unit_price;
                $existingCartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'item_id' => $itemId,
                    'qty' => $qty,
                    'unit_price' => $item->price,
                    'total_amount' => $item->price * $qty,
                    'shipping' => 0,
                ]);
            }

            $this->updateCartTotals($cart);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error adding item to cart: ' . $e->getMessage(), [
                'user_id' => $userId,
                'item_id' => $itemId,
                'qty' => $qty
            ]);
            throw $e;
        }
    }

    /**
     * Update item quantity in cart
     *
     * @param int $userId
     * @param int $itemId
     * @param int $qty
     * @return void
     */
    public function updateItemQuantity(int $userId, int $itemId, int $qty): void
    {
        try {
            $cart = $this->getUserCart($userId);
            if (!$cart) {
                throw new \Exception('Cart not found');
            }

            $cartItem = $cart->cartItem()->where('item_id', $itemId)->first();
            if (!$cartItem) {
                throw new \Exception('Item not found in cart');
            }

            if ($qty <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->qty = $qty;
                $cartItem->total_amount = $cartItem->qty * $cartItem->unit_price;
                $cartItem->save();
            }

            $this->updateCartTotals($cart);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error updating item quantity: ' . $e->getMessage(), [
                'user_id' => $userId,
                'item_id' => $itemId,
                'qty' => $qty
            ]);
            throw $e;
        }
    }

    /**
     * Remove item from cart
     *
     * @param int $userId
     * @param int $itemId
     * @return void
     */
    public function removeItemFromCart(int $userId, int $itemId): void
    {
        try {
            $cart = $this->getUserCart($userId);
            if ($cart) {
                $cart->cartItem()->where('item_id', $itemId)->delete();
                $this->updateCartTotals($cart);
            }
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error removing item from cart: ' . $e->getMessage(), [
                'user_id' => $userId,
                'item_id' => $itemId
            ]);
            throw $e;
        }
    }

    /**
     * Clear cart
     *
     * @param int $userId
     * @return void
     */
    public function clearCart(int $userId): void
    {
        try {
            $cart = $this->getUserCart($userId);
            if ($cart) {
                $cart->cartItem()->delete();
                $this->updateCartTotals($cart);
            }
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error clearing cart: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Update cart totals
     *
     * @param Cart $cart
     * @return void
     */
    private function updateCartTotals(Cart $cart): void
    {
        $qty = 0;
        $subtotal = 0;
        $shipping = 0;

        foreach ($cart->cartItem()->get() as $item) {
            $qty += $item->qty;
            $subtotal += $item->total_amount;
            $shipping += $item->shipping;
        }

        $cart->update([
            'total_cart' => $subtotal + $shipping,
            'shipping' => $shipping,
            'total_cart_quantity' => $qty,
        ]);
    }
}

