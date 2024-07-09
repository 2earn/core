<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserBalanceSMS extends Component
{
    public function render()
    {
        return view('livewire.user-balance-s-m-s')->extends('layouts.master')->section('content');
    }
}
