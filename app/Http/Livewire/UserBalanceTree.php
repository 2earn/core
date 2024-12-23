<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserBalanceTree extends Component
{
    public function render()
    {
        return view('livewire.user-balance-tree')->extends('layouts.master')->section('content');
    }
}
