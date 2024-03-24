<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EvolutionArbre extends Component
{
    public function render()
    {
        return view('livewire.evolution-arbre')->extends('layouts.master')->section('content');
    }
}
