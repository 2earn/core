<?php

namespace App\Livewire;

use Livewire\Component;

class SalesTracking extends Component
{
    public function render()
    {
        return view('livewire.sales-tracking')->extends('layouts.master')->section('content');
    }
}
