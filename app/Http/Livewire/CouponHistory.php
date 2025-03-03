<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CouponHistory extends Component
{
    public function render()
    {
        return view('livewire.coupon-history')->extends('layouts.master')->section('content');
    }
}
