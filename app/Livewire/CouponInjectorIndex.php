<?php

namespace App\Livewire;

use Livewire\Component;

class CouponInjectorIndex extends Component
{
    public function render()
    {
        return view('livewire.coupon-injector-index')->extends('layouts.master')->section('content');
    }
}
