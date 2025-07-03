<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use Illuminate\Http\Request;
use Livewire\Component;

class UserBalanceBFS extends Component
{
    public $bfss = [];
    public $type;
    public $totalBfs;

    public function mount(Request $request)
    {

        $this->type = $request->input('type');
        if (is_null($this->type)) {
            $this->type = "ALL";
        }

        $this->bfss = Balances::getStoredUserBalances(Auth()->user()->idUser, Balances::BFSS_BALANCE);
        $balances = Balances::getStoredUserBalances(Auth()->user()->idUser);
        $this->totalBfs = Balances::getTotalBfs($balances);
    }

    public function render()
    {
        return view('livewire.user-balance-b-f-s')->extends('layouts.master')->section('content');
    }
}
