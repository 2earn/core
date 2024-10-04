<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GeneratingPDFReport extends Component
{
    public function render()
    {
        return view('livewire.generating-p-d-f-report')->extends('layouts.master')->section('content');
    }
}
