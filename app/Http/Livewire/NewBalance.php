<?php

namespace App\Http\Livewire;

use App\Models\BFSsBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Services\Balances\BalancesFacade;
use Core\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        $userId = 197604342;
        $balances = UserCurrentBalanceHorisontal::where('user_id', $userId)->first();
        $balances->setBfssBalance(BFSsBalances::BFS_50, 100);
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
