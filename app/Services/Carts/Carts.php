<?php

namespace App\Services\Carts;


use App\Models\CartItem;
use  App\Models\Cart;

class Carts
{
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
    }

    public static function addItemToCart($item)
    {
        $cart = self::getOrCreateCart();
        $cartItem = CartItem::create([
            'item_id' => $item->id,
            'qty' => 1,
            'shipping' => $item->shipping,
            'unit_price' => $item->price,
            'total_amount' => $item->price,
        ]);
        $cart->cartItem()->save($cartItem);
    }
}
