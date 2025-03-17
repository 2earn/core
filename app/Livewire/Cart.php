<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use  App\Services\Carts\Carts;

class Cart extends Component
{
    public $cart;
    protected $listeners = ['itemAddedToCart' => 'updateCart'];

    public function updateCart()
    {
        $this->cart = Carts::getOrCreateCart();
        $this->dispatch('updateCart');
    }

    public function removeItem(CartItem $cartItem)
    {
        Carts::removeItemFromCart($cartItem);
        $this->dispatch('updateCart');
        $this->dispatch('removeItemFromCart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.cart');
    }
}
