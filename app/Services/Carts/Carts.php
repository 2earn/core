<?php

namespace App\Services\Carts;


use App\Models\CartItem;
use  App\Models\Cart;

class Carts
{
    public static function updateCart()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $qty = 0;
        $subtotal = 0;
        $shipping = 0;
        foreach ($cart->cartItem()->get() as $item) {
            $qty += $item->qty;
            $subtotal += $item->total_amount;
            $subtotal += $item->shipping;
            $shipping += $item->shipping;
        }
        $cart->update([
            'total_cart' => $subtotal,
            'shipping' => $shipping,
            'total_cart_quantity' => $qty,
        ]);
    }

    public static function getOrCreateCart()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        if (!is_null($cart)) {
            return $cart;
        }
        return Cart::create([
            'total_cart' => 0,
            'total_cart_quantity' => 0,
            'user_id' => auth()->user()->id
        ]);
    }

    public static function removeItemFromCart($cartItem)
    {
        $cartItem = CartItem::find($cartItem)->first();
        $cartItem->delete();
        Carts::updateCart();
    }

    public static function addItemToCart($item, $qty = 1)
    {
        $cart = self::getOrCreateCart();
        $existingCartItem = $cart->cartItem()->where('item_id', $item->id)->first();

        if ($existingCartItem) {
            $existingCartItem->qty += $qty;
            $existingCartItem->total_amount = $existingCartItem->qty * $existingCartItem->unit_price;
            $existingCartItem->shipping = $existingCartItem->price * mt_rand(5, 15) / 100;
            $existingCartItem->save();
        } else {
            $cartItem = CartItem::create([
                'item_id' => $item->id,
                'qty' => $qty,
                'shipping' => $item->price * mt_rand(5, 15) / 100,
                'unit_price' => $item->price,
                'total_amount' => $item->price,
            ]);
            $cart->cartItem()->save($cartItem);
        }
        Carts::updateCart();
    }
}
