<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        $idUser = "197604171";
        // dd(BalancesFacade::getReference(44));
        dd(

            BalancesFacade::getCash($idUser),
            BalancesFacade::getBfss($idUser),
            BalancesFacade::getDiscount($idUser),
            BalancesFacade::getSms($idUser),
            BalancesFacade::getTree($idUser),
        );
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
