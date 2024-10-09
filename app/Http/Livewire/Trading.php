<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Trading extends Component
{
    public function render()
    {
        return view('livewire.trading')->extends('layouts.master')->section('content');
    }
}
