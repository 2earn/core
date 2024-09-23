<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RunningBusiness extends Component
{
    public function render()
    {
        return view('livewire.running-business')->extends('layouts.master')->section('content');
    }
}
