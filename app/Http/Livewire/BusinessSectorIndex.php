<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BusinessSectorIndex extends Component
{
    public function render()
    {
        return view('livewire.business-sector-index')->extends('layouts.master')->section('content');
    }
}
