<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EBusinessCard extends Component
{
    public function render()
    {
        return view('livewire.e-business-card')->extends('layouts.master')->section('content');
    }
}
