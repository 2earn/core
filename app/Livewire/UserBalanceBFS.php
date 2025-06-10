<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use Livewire\Component;

class UserBalanceBFS extends Component
{
    public $bfss = [];

    public function mount()
    {
        $this->bfss = Balances::getStoredUserBalances(Auth()->user()->idUser, Balances::BFSS_BALANCE);
    }

    public function render()
    {
        return view('livewire.user-balance-b-f-s')->extends('layouts.master')->section('content');
    }
}
