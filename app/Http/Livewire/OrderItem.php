<?php

namespace App\Http\Livewire;

use App\Services\Orders\OrderingSimulation;
use Livewire\Component;

class OrderItem extends Component
{

    public function render()
    {
        return view('livewire.order-item');
    }
}
