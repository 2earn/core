<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserBalanceChance extends Component
{
    public function render()
    {
        return view('livewire.user-balance-chance')->extends('layouts.master')->section('content');
    }
}
