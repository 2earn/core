<?php

namespace App\Http\Livewire;

use App\Services\Carts\Carts;
use Livewire\Component;

class OrderSummary extends Component
{
    public $cart;

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.order-summary')->extends('layouts.master')->section('content');
    }
}
