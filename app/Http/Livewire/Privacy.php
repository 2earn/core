<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Privacy extends Component
{
    public function render()
    {
        return view('livewire.privacy')->extends('layouts.master-without-nav-not-fluid')->section('content');
    }
}
