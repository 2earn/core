<?php

namespace App\Http\Livewire;

use Livewire\Component;

class JobOpportunities extends Component
{
    public function render()
    {
        return view('livewire.job-opportunities')->extends('layouts.master')->section('content');
    }
}
