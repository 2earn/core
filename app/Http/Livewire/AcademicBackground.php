<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AcademicBackground extends Component
{
    public function render()
    {
        return view('livewire.academic-background')->extends('layouts.master')->section('content');
    }
}
