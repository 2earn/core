<?php

namespace App\Livewire;

use Livewire\Component;

class HistoriqueRecuperation extends Component
{
    public function render()
    {
        return view('livewire.historique-recuperation')->extends('layouts.master')->section('content');
    }
}
