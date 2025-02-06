<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CouponIndex extends Component
{
    public function render()
    {
        return view('livewire.coupon-index')->extends('layouts.master')->section('content');
    }
}
