<?php

namespace App\Http\Livewire;

use App\Models\BFSsBalances;
use App\Models\UserCurrentBalanceHorisontal;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        $userId = 197604342;
        $balances = UserCurrentBalanceHorisontal::where('user_id', $userId)->first();
        dump($balances->getBfssBalance(BFSsBalances::BFS_100));
        dump($balances->getBfssBalance(BFSsBalances::BFS_50));
        $balances->setBfssBalance(BFSsBalances::BFS_100, 100);
        $balances->setBfssBalance(BFSsBalances::BFS_50, 100);
        dump($balances->getBfssBalance(BFSsBalances::BFS_100));
        dump($balances->getBfssBalance(BFSsBalances::BFS_50));
        dump($balances->getBfssBalance(BFSsBalances::BFS_50));

        dump(\App\Services\Balances\Balances::getTotolBfs($balances));
        $balances = UserCurrentBalanceHorisontal::where('user_id', $userId)->first();
        dd($balances);
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
