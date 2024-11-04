<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DealsShow extends Component
{
    public function render()
    {
        return view('livewire.deals-show')->extends('layouts.master')->section('content');
    }
}
