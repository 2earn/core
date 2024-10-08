<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Trading extends Component
{
    public $deadline;

    public function mount()
    {
        $this->deadline = DB::table('settings')->where('ParameterName', 'trading_cs')->value('StringValue');
    }

    public function render()
    {
        return view('livewire.trading')->extends('layouts.master')->section('content');
    }
}
