<?php

namespace App\Livewire;

use App\Services\Carts\Carts;
use Livewire\Component;

class ItemsShow extends Component
{
    public $item;
    public $quantityToAdd = 1;
    public $orderedQty = 0;

    protected $listeners = [
        'updated-cart' => '$refresh',
    ];

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
        $this->dispatch('created');
        $this->dispatch('update-cart');
    }

    public function addToCard()
    {
        Carts::addItemToCart($this->item);
        $this->dispatch('created');
        $this->dispatch('update-cart');
    }

    public function removeFromCard()
    {
        $cartItem = Carts::getCardItemByItemIdFromSession($this->item->id);
        if (!is_null($cartItem)) {
            Carts::removeItemFromCart($cartItem->id);
            $this->dispatch('created');
            $this->dispatch('update-cart');
        }
    }

    public function render()
    {
        $this->orderedQty = Carts::getQtyCardItemFromSession($this->item->id);
        return view('livewire.items-show');
    }
}
