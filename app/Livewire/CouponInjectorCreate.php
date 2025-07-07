<?php

namespace App\Livewire;

use Livewire\Component;

class CouponInjectorCreate extends Component
{
    public function render()
    {
        return view('livewire.coupon-injector-create')->extends('layouts.master')->section('content');
    }
}
