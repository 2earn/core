<?php

namespace App\Livewire;

use Livewire\Component;

class EntretienArbre extends Component
{
    public function render()
    {
        return view('livewire.entretien-arbre')->extends('layouts.master')->section('content');
    }
}
