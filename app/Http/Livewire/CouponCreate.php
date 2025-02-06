<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CouponCreate extends Component
{
    public function render()
    {
        return view('livewire.coupon-create')->extends('layouts.master')->section('content');
    }
}
