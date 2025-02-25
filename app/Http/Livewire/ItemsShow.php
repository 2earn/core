<?php

namespace App\Http\Livewire;

use App\Services\Carts\Carts;
use Livewire\Component;

class ItemsShow extends Component
{
    public $item;

    public function mount($item)
    {
        $this->item = $item;
    }

    public function addToCard()
    {
        Carts::addItemToCart($this->item);
        $this->emit('itemAddedToCart');
    }

    public function render()
    {
        return view('livewire.items-show');
    }
}
