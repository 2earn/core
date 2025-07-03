<?php

namespace App\Livewire;

use Livewire\Component;

class SettlementTracking extends Component
{
    public function render()
    {
        return view('livewire.settlement-tracking')->extends('layouts.master')->section('content');
    }
}
