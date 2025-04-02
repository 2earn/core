<?php

namespace App\Livewire;

use Livewire\Component;

class StripView extends Component
{
    public function render()
    {
        return view('livewire.strip-view')->extends('layouts.master')->section('content');
    }
}
