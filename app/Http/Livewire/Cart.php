<?php

namespace App\Http\Livewire;

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
        $this->dispatchBrowserEvent('updateCart');
    }

    public function removeItem(CartItem $cartItem)
    {
        Carts::removeItemFromCart($cartItem);
        $this->dispatchBrowserEvent('updateCart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.cart');
    }
}
