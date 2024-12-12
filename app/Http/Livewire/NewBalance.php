<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Core\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
