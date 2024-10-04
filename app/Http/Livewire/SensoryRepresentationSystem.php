<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SensoryRepresentationSystem extends Component
{
    public function render()
    {
        return view('livewire.sensory-representation-system')->extends('layouts.master')->section('content');
    }
}
