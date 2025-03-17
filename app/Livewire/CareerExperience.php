<?php

namespace App\Livewire;

use Livewire\Component;

class CareerExperience extends Component
{
    public function render()
    {
        return view('livewire.career-experience')->extends('layouts.master')->section('content');
    }
}
