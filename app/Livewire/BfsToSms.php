<?php

namespace App\Livewire;

use Livewire\Component;

class BfsToSms extends Component
{
    public $prix_sms = 0;
    public function render()
    {
        return view('livewire.bfs-to-sms');
    }
}
