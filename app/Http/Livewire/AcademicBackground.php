<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AcademicBackground extends Component
{
    public function render()
    {
        return view('livewire.academic-background')->extends('layouts.master')->section('content');
    }
}
