<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        $idUser = "197604171";
        // dd(BalancesFacade::getReference(44));
//        dd(
//
//            BalancesFacade::getCash($idUser),
//            BalancesFacade::getBfss($idUser),
//            BalancesFacade::getDiscount($idUser),
//            BalancesFacade::getSms($idUser),
//            BalancesFacade::getTree($idUser),
//        );

        // sum

         $data = DB::table(function ($query) {
            $query->select('idUser', 'u.idamount', 'Date', 'u.idBalancesOperation', 'b.operation', DB::raw('CASE WHEN b.io = "I" THEN value ELSE -value END as value'))
                ->from('user_balances as u')
                ->join('balance_operations as b', 'u.idBalancesOperation', '=', 'b.id')
                ->whereNotIn('u.idamount', [4])
                ->orderBy('idUser')
                ->orderBy('u.idamount')
                ->orderBy('Date');
        }, 'a')
            ->select('a.idamount', DB::raw('SUM(a.value) as value'))
            ->groupBy('a.idamount')
            ->orderBy('a.idamount')
            ->union(DB::table('user_balances')
                ->select(DB::raw('7 as idamount'), DB::raw('SUM(value) as value'))
                ->where('idBalancesOperation', 48))
            ->orderBy('idamount')
            ->get();

        dd($data,getUserListCards());



        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
