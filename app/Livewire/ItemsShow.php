<?php

namespace App\Livewire;

use App\Services\Carts\Carts;
use Livewire\Component;

class ItemsShow extends Component
{
    public $item;
    public $quantityToAdd = 1;
    public $orderedQty = 0;

    public function mount($item)
    {
        $this->item = $item;
    }

    public function addMoreToCard()
    {
        if ($this->quantityToAdd > 0) {
            Carts::addItemToCart($this->item, $this->quantityToAdd);
        }
        $this->quantityToAdd = 1;

        $this->dispatch('itemAddedToCart');
    }

    public function addToCard()
    {
        Carts::addItemToCart($this->item);
        $this->dispatch('itemAddedToCart');
    }

    public function render()
    {
        $this->orderedQty = Carts::getQtyCardItemFromSession($this->item->id);
        return view('livewire.items-show');
    }
}
