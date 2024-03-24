<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserBalanceDB extends Component
{
    public function render()
    {
        return view('livewire.user-balance-d-b')->extends('layouts.master')->section('content');
    }
}
