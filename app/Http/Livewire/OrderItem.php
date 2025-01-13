<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrderItem extends Component
{
    const CURRENCY = '$';
    public function render()
    {
        return view('livewire.order-item');
    }
}
