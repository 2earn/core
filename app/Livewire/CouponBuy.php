<?php

namespace App\Livewire;

use App\Enums\CouponStatusEnum;
use App\Enums\OrderEnum;
use App\Services\Coupon\CouponService;
use App\Services\Items\ItemService;
use App\Services\Orders\Ordering;
use App\Services\Orders\OrderService;
use App\Services\Platform\PlatformService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CouponBuy extends Component
{
    const DELAY_FOR_COUPONS_SIMULATION = 5;

    protected CouponService $couponService;
    protected PlatformService $platformService;
    protected OrderService $orderService;
    protected ItemService $itemService;

    public $amount = 0;
    public $displayedAmount;
    public $coupons;
    public $equal = false;
    public $simulated = false;
    public $buyed = false;
    public $order = null;
    public $linkOrder = null;
    public $lastValue;
    public $idPlatform;
    public $preSumulationResult;
    public $result;
    public $pre;
    public $maxAmount = 0;
    public $time;


    public $listeners = [
        'simulateCoupon' => 'simulateCoupon',
        'BuyCoupon' => 'BuyCoupon',
        'ConfirmPurchase' => 'ConfirmPurchase',
        'CancelPurchase' => 'CancelPurchase',
        'consumeCoupon' => 'consumeCoupon'
    ];

    public function boot(
        CouponService $couponService,
        PlatformService $platformService,
        OrderService $orderService,
        ItemService $itemService
    ) {
        $this->couponService = $couponService;
        $this->platformService = $platformService;
        $this->orderService = $orderService;
        $this->itemService = $itemService;
    }

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');
        $this->amount = 0;

        $this->maxAmount = $this->couponService->getMaxAvailableAmount(
            $this->idPlatform
        );

        $this->time = getSettingIntegerParam('DELAY_FOR_COUPONS_SIMULATION', self::DELAY_FOR_COUPONS_SIMULATION);
    }


    public function consumeCoupon($id)
    {
        $couponToUpdate = $this->couponService->findCouponById($id);
        if ($couponToUpdate && !$couponToUpdate->consumed) {
            $this->couponService->updateCoupon($couponToUpdate, [
                'user_id' => auth()->user()->id,
                'consumption_date' => now(),
                'status' => CouponStatusEnum::consumed->value,
                'consumed' => true
            ]);
        }
        foreach ($this->coupons as &$coupon) {
            if ($coupon->id == $id) {
                $coupon = $couponToUpdate;
            }
        }
    }

    public function CancelPurchase()
    {
        foreach ($this->preSumulationResult['coupons'] as $coupon) {
            $this->couponService->updateCoupon($coupon, [
                'status' => CouponStatusEnum::available->value,
                'user_id' => null
            ]);
        }
        foreach ($this->result['coupons'] as $coupon) {
            $this->couponService->updateCoupon($coupon, [
                'status' => CouponStatusEnum::available->value,
                'user_id' => null
            ]);
        }
        $this->redirect(route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform]));
    }

    public function ConfirmPurchase($pre)
    {
        $this->pre = $pre;
        $this->amount = $pre == 1 ? $this->amount : $this->amount + $this->lastValue;
        $pre == 1 ? $this->BuyCoupon($this->preSumulationResult['coupons']) : $this->BuyCoupon($this->result['coupons']);
    }

    public function simulateCoupon()
    {
        // Simulate coupon purchase using service
        $result = $this->couponService->simulateCouponPurchase(
            $this->idPlatform,
            auth()->user()->id,
            $this->displayedAmount,
            $this->time
        );

        if (!$result['success']) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])
                ->with('danger', trans($result['message']));
        }

        // Update component state with simulation results
        $this->equal = $result['equal'];
        $this->amount = $result['amount'];
        $this->lastValue = $result['lastValue'];
        $this->coupons = $result['coupons'];
        $this->preSumulationResult = $result['preSimulationResult'];
        $this->result = $result['alternativeResult'];
        $this->simulated = true;

    }

    public function BuyCoupon($cpns)
    {
        $platform = $this->platformService->getById($this->idPlatform);
        $coupon = $this->itemService->findByRefAndPlatform('#0001', $this->idPlatform);

        // Process coupon purchase using service
        $result = $this->couponService->buyCoupon(
            $cpns,
            auth()->user()->id,
            $this->idPlatform,
            $platform->name,
            $coupon->id
        );

        if (!$result['success']) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])
                ->with('danger', trans($result['message']));
        }

        // Update component state with purchase results
        $this->coupons = $result['coupons'];
        $this->displayedAmount = $result['totalAmount'];
        $this->buyed = true;
        $this->linkOrder = route('orders_detail', ['locale' => app()->getLocale(), 'id' => $result['order']->id]);
        $this->order = $result['order'];
    }

    public function getCouponsForAmount($amount): array
    {
        return $this->couponService->getCouponsForAmount(
            $this->idPlatform,
            auth()->user()->id,
            $amount,
            $this->time,
            $this->equal
        );
    }

    public function render()
    {
        $params = ['platform' => $this->platformService->getById($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
