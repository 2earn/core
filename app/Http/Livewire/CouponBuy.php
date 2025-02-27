<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CouponBuy extends Component
{
    public function render()
    {
        return view('livewire.coupon-buy')->extends('layouts.master')->section('content');
    }
}
