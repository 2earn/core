<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DealsCreateUpdate extends Component
{
    public function render()
    {
        return view('livewire.deals-create-update')->extends('layouts.master')->section('content');
    }
}
