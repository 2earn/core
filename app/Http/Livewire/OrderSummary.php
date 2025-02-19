<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrderSummary extends Component
{
    public $total = 0;
    public $items = [];

    public function render()
    {
        return view('livewire.order-summary')->extends('layouts.master')->section('content');
    }
}
