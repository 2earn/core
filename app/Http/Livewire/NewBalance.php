<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewBalance extends Component
{
    public function SommeSold($type){
        return DB::table(function ($query) use ($type) {
            $query->select('beneficiary_id',  'u.created_at', 'u.balance_operation_id', 'b.operation', DB::raw('CASE WHEN b.io = "I" THEN value ELSE -value END as value'))
                ->from($type.' as u')
                ->join('balance_operations as b', 'u.balance_operation_id', '=', 'b.id')
                ->orderBy('beneficiary_id')
                ->orderBy('u.created_at');
        }, 'a')->get(DB::raw('sum(value) as somme'))->pluck('somme')->first() ;
    }
    public function render()
    {
        $idUser = "197604171";



        dd($this->sommeSold('cash_balances'));

     dd(getUserListCards());


        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
