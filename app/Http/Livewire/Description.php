<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Description extends Component
{
    public function render()
    {
        return view('livewire.description')->extends('layouts.master')->section('content');
    }
}
