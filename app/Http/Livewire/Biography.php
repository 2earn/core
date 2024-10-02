<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Biography extends Component
{
    public function render()
    {
        return view('livewire.biography')->extends('layouts.master')->section('content');
    }
}
