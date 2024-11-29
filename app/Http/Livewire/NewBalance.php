<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        dd(BalancesFacade::getReference(44));
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
