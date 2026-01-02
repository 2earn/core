<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use App\Services\Coupon\BalanceInjectorCouponService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CouponInjectorRunner extends Component
{
    protected BalanceInjectorCouponService $couponService;

    public $pin;

    protected $listeners = [
        'runCoupon' => 'runCoupon'
    ];

    public function boot(BalanceInjectorCouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function runCoupon()
    {
        if (is_null($this->pin) || empty($this->pin)) {
            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Bad pin code'));
        }

        try {
            $coupon = $this->couponService->getByPin($this->pin);

            if (is_null($coupon) || $coupon->consumed == 1) {
                return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])
                    ->with('warning', Lang::get('Using a bad Coupon pin or a consumed one'));
            }

            Balances::injectCouponBalance($coupon);

            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Recharge balance operation ended with success'));
        } catch (\Exception $e) {
            return redirect()->route('coupon_injector_runner', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Recharge balance operation failed'));
        }

    }

    public function render()
    {
        return view('livewire.coupon-injector-runner')->extends('layouts.master')->section('content');
    }
}
