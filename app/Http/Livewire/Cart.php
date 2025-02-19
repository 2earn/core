<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Cart extends Component
{
    public $total = 0;
    public $items = [];

    public function render()
    {
        return view('livewire.cart');
    }
}
