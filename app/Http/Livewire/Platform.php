<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Platform extends Component
{
    public function render()
    {
        return view('livewire.platform')->extends('layouts.master')->section('content');
    }
}
