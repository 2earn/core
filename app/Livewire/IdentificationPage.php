<?php

namespace App\Livewire;

use Livewire\Component;

class IdentificationPage extends Component
{
    public function render()
    {
        return view('livewire.identification-page')->extends('layouts.master')->section('content');
    }
}

