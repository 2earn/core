<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Livewire\Component;

class CouponBuy extends Component
{
    public $amount;
    public $coupon;
    public $listeners = [
        'simulateCoupon' => 'simulateCoupon',
        'BuyCoupon' => 'BuyCoupon'
    ];

    public function mount()
    {
        $this->amount = 0;
    }

    public function simulateCoupon($amount)
    {

    }

    public function BuyCoupon($amount)
    {

    }

    public function getCouponsForAmount($amount)
    {

        $availableCoupons = Coupon::where('status', 'available')
            ->orderBy('value', 'desc')
            ->get();

        $selectedCoupons = [];
        $total = 0;

        foreach ($availableCoupons as $coupon) {
            if ($total + $coupon->value <= $amount) {
                $selectedCoupons[] = $coupon;
                $total += $coupon->value;
            }

            if ($total == $amount) {
                break;
            }
        }

        if ($total == $amount) {
            return response()->json([
                'amount' => $amount,
                'coupons' => $selectedCoupons,
            ]);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.coupon-buy')->extends('layouts.master')->section('content');
    }
}
