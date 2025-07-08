<?php

namespace App\Livewire;

use App\Models\BalanceInjectorCoupon;
use App\Services\Balances\Balances;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CouponInjectorRunner extends Component
{

    public $pin;

    protected $listeners = [
        'runCoupon' => 'runCoupon'
    ];

    public function runCoupon()
    {
        if (is_null($this->pin) || empty($this->pin)) {
            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])->with('danger', Lang::get('Bad pin code'));
        }
        try {
            $coupon = BalanceInjectorCoupon::where('pin', $this->pin)->first();
            if ($coupon) {
                if ($coupon->consumed == 1) {
                    return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])->with('warning', Lang::get('Using a consumed Coupons'));
                }
                Balances::injectCouponBalance($coupon);
            }
            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])->with('success', Lang::get('Rechange balance operation ended with success'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])->with('danger', Lang::get('Rechange balance operation failed'));
        }

    }

    public function render()
    {
        return view('livewire.coupon-injector-runner')->extends('layouts.master')->section('content');
    }
}
