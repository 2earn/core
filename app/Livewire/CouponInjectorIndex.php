<?php

namespace App\Livewire;

use App\Models\BalanceInjectorCoupon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CouponInjectorIndex extends Component
{
    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        try {
            BalanceInjectorCoupon::findOrFail($id)->delete();
            return redirect()->route('coupon_injector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Coupons deleted successfully'));
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('coupon_injector_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.coupon-injector-index')->extends('layouts.master')->section('content');
    }
}
