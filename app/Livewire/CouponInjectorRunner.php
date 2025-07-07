<?php

namespace App\Livewire;

use Livewire\Component;

class CouponInjectorRunner extends Component
{
    public function render()
    {
        return view('livewire.coupon-injector-runner')->extends('layouts.master')->section('content');
    }
}
