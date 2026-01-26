<?php

namespace App\Livewire;

use App\Enums\BalanceEnum;
use App\Models\BalanceInjectorCoupon;
use App\Services\Coupon\BalanceInjectorCouponService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Livewire\Component;

class CouponInjectorCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_injector_index';

    protected BalanceInjectorCouponService $balanceInjectorCouponService;

    public $attachment_date;
    public $numberOfCoupons;
    public $value;
    public $type;
    public $category_id;
    public $allCategories;

    protected $rules = [
        'numberOfCoupons' => 'required|numeric|min:1|max:100',
        'category_id' => 'required',
        'attachment_date' => ['required', 'after_or_equal:today'],
        'value' => 'required|numeric|min:0.01',
    ];

    public function boot(BalanceInjectorCouponService $balanceInjectorCouponService)
    {
        $this->balanceInjectorCouponService = $balanceInjectorCouponService;
    }

    public function mount()
    {
        $this->allCategories = [
            BalanceEnum::CASH, BalanceEnum::BFS, BalanceEnum::DB
        ];
        $this->category_id = BalanceEnum::CASH->value;
    }

    public function store()
    {
        $this->validate();

        // Prepare coupon data
        $couponData = [
            'attachment_date' => $this->attachment_date,
            'value' => $this->value,
            'category' => intval($this->category_id),
            'consumed' => false
        ];

        // Create coupons using service
        $result = $this->balanceInjectorCouponService->createMultipleCoupons(
            $this->numberOfCoupons,
            $couponData,
            $this->type
        );

        if (!$result['success']) {
            return redirect()->route('coupon_injector_create', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get($result['message']));
        }

        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])
            ->with('success', Lang::get($result['message']));
    }

    // ...existing code...
    public function cancel()
    {
        return redirect()->route('coupon_injector_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Coupons operation canceled'));
    }

    public function render()
    {
        return view('livewire.coupon-injector-create')->extends('layouts.master')->section('content');
    }
}
