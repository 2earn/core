<?php

namespace App\Livewire;

use Livewire\Component;

class CommissionFormulaIndex extends Component
{
    public function render()
    {
        return view('livewire.commission-formula-index')->extends('layouts.master')->section('content');
    }
}
