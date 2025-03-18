<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use  App\Services\Carts\Carts;

class Cart extends Component
{
    public $cart;

    protected $listeners = [
        'update-cart' => 'updateCart',
        'item-added-to-cart' => 'updateCart',
        'removeCartItem' => 'removeCartItem',
    ];

    public function updateCart()
    {
        $this->cart = Carts::getOrCreateCart();
    }

    public function removeCartItem($cartItem)
    {
        Carts::removeItemFromCart($cartItem);
        $this->dispatch('update-cart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.cart');
    }
}
