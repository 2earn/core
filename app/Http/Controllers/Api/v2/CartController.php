<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
      private CartService $cartService;

      public function __construct(CartService $cartService)
      {
            $this->cartService = $cartService;
      }

      /**
       * Get user cart
       */
      public function index(int $userId)
      {
            try {
                  $cart = $this->cartService->getUserCart($userId);
                  if (!$cart) {
                        return response()->json(['status' => true, 'data' => null]);
                  }

                  $items = $cart->cartItem()->with('item')->get();

                  return response()->json([
                        'status' => true,
                        'data' => [
                              'id' => $cart->id,
                              'total_cart' => $cart->total_cart,
                              'total_cart_quantity' => $cart->total_cart_quantity,
                              'shipping' => $cart->shipping,
                              'items' => $items
                        ]
                  ]);
            } catch (\Exception $e) {
                  return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
      }

      /**
       * Add item to cart
       */
      public function add(Request $request, int $userId)
      {
            $validator = Validator::make($request->all(), [
                  'item_id' => 'required|integer|exists:items,id',
                  'qty' => 'nullable|integer|min:1'
            ]);

            if ($validator->fails()) {
                  return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            try {
                  $this->cartService->addItemToCart($userId, $request->input('item_id'), $request->input('qty', 1));
                  return response()->json(['status' => true, 'message' => 'Item added to cart'], 201);
            } catch (\Exception $e) {
                  return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
      }

      /**
       * Update item quantity in cart
       */
      public function update(Request $request, int $userId, int $itemId)
      {
            $validator = Validator::make($request->all(), [
                  'qty' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                  return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            try {
                  $this->cartService->updateItemQuantity($userId, $itemId, $request->input('qty'));
                  return response()->json(['status' => true, 'message' => 'Cart updated']);
            } catch (\Exception $e) {
                  return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
      }

      /**
       * Remove item from cart
       */
      public function remove(int $userId, int $itemId)
      {
            try {
                  $this->cartService->removeItemFromCart($userId, $itemId);
                  return response()->json(['status' => true, 'message' => 'Item removed from cart']);
            } catch (\Exception $e) {
                  return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
      }

      /**
       * Clear cart
       */
      public function clear(int $userId)
      {
            try {
                  $this->cartService->clearCart($userId);
                  return response()->json(['status' => true, 'message' => 'Cart cleared']);
            } catch (\Exception $e) {
                  return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
      }
}
