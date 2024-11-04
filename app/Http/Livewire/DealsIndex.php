<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DealsIndex extends Component
{
    public function render()
    {
        return view('livewire.deals-index')->extends('layouts.master')->section('content');
    }
}
