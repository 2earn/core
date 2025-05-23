<?php

namespace App\Livewire;

use App\Models\CartItem;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use  App\Services\Carts\Carts;

class Cart extends Component
{
    public $cart;
    public $date_rendered;

    protected $listeners = [
        'update-cart' => '$refresh',
        'removeCartItem' => 'removeCartItem',
    ];

    public function removeCartItem($cartItem)
    {
        Log::notice('removeCartItem '.$cartItem);
         Carts::removeItemFromCart($cartItem);
        $this->dispatch('updated-cart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        $this->date_rendered=now(); return view('livewire.cart');
    }
}
