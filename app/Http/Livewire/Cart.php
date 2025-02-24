<?php

namespace App\Http\Livewire;

use App\Models\CartItem;
use App\Models\Item;
use Livewire\Component;
use  App\Services\Carts\Carts;

class Cart extends Component
{
    public $cart;

    public function removeItem(CartItem $cartItem)
    {
        Carts::removeItemFromCart($cartItem);
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.cart');
    }
}
