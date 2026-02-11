<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use App\Services\Platform\PlatformService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CouponCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_index';

    protected CouponService $couponService;
    protected PlatformService $platformService;

    public $pins;
    public $sn;
    public $attachment_date;
    public $value;
    public $platform_id;
    public $selectPlatforms;

      protected $rules = [
        'pins' => 'required|unique:coupons,pin',
        'sn' => 'required|unique:coupons,sn',
        'platform_id' => 'required',
        'attachment_date' => ['required', 'after_or_equal:today'],
        'value' => 'required|numeric|min:0.01',

    ];

    public function boot(CouponService $couponService, PlatformService $platformService)
    {
        $this->couponService = $couponService;
        $this->platformService = $platformService;
    }

    public function mount()
    {
        // Get platforms formatted for select dropdown using service
        $this->selectPlatforms = $this->platformService->getSelectPlatformsWithCouponDeals();
        // Initialize platform_id as null to show placeholder
        $this->platform_id = null;

        // Log if no platforms are available
        if (empty($this->selectPlatforms)) {
            \Log::warning('No platforms with coupon deals available for coupon creation');
        }
    }

    public function store()
    {
        $this->validate();

        try {
            $couponData = [
                'attachment_date' => $this->attachment_date,
                'value' => $this->value,
                'platform_id' => $this->platform_id,
                'consumed' => false
            ];

            $pins = explode(',', $this->pins);
            $sns = explode(',', $this->sn);

            $createdCount = $this->couponService->createMultipleCoupons($pins, $sns, $couponData);

            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Coupons created Successfully') . " ({$createdCount})");
        } catch (\Exception $exception) {
            return redirect()->route('coupon_create', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Coupons creation Failed') . ' ' . $exception->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('coupon_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Coupons operation canceled'));
    }

    public function render()
    {
        return view('livewire.coupon-create')->extends('layouts.master')->section('content');
    }
}
