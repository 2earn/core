<?php

namespace App\Http\Livewire;

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
        dd($this->item);
    }

    public function render()
    {
        return view('livewire.items-show');
    }
}
