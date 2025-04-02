<?php

namespace App\Livewire;

use Livewire\Component;

class CDPersonality extends Component
{
    public function render()
    {
        return view('livewire.c-d-personality')->extends('layouts.master')->section('content');
    }
}
