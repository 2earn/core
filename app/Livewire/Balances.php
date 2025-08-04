<?php

namespace App\Livewire;

use Core\Models\Amount;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Balances extends Component
{

    public $operation;
    public $io;
    public $source;
    public $mode;
    public $amounts_id;
    public $note;
    public $modify_amount;
    public $search = '';
    public $currentRouteName;

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function render()
    {
        return view('livewire.balances')->extends('layouts.master')->section('content');
    }
}
