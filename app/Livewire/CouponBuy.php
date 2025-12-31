<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use App\Services\Items\ItemService;
use App\Services\Orders\OrderService;
use App\Services\Orders\Ordering;
use App\Services\Platform\PlatformService;
use Core\Enum\CouponStatusEnum;
use Core\Enum\OrderEnum;
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
        $this->equal = false;

        if ($this->displayedAmount == "" || $this->displayedAmount == "0" || intval($this->displayedAmount) < 1) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Wrong wintered amount'));
        }

        $this->amount = $this->displayedAmount;
        $this->preSumulationResult = $this->getCouponsForAmount($this->amount);
        if ($this->amount) {
            if ($this->preSumulationResult['amount'] == $this->displayedAmount) {
                $this->equal = true;
            } else {
                $this->equal = false;
            }
            if (is_null($this->preSumulationResult)) {
                return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Amount simulation failed'));
            }
            $this->result = $this->getCouponsForAmount($this->preSumulationResult['lastValue'] + $this->amount);
            if ($this->equal) {
                $this->lastValue = $this->preSumulationResult['lastValue'];
                $this->amount = $this->preSumulationResult['amount'];
                $this->coupons = $this->preSumulationResult['coupons'];
            } else {
                $this->lastValue = $this->preSumulationResult['lastValue'];
                $this->amount = $this->preSumulationResult['amount'];
                $this->coupons = $this->result['coupons'];
            }
        } else {
            $this->coupons = [];
        }

        $this->simulated = true;

    }

    public function BuyCoupon($cpns)
    {
        $platform = $this->platformService->getPlatformById($this->idPlatform);
        $order = $this->orderService->createOrder([
            'user_id' => auth()->user()->id,
            'platform_id' => $this->idPlatform,
            'note' => 'Coupons buy from' . ' :' . $this->idPlatform . '-' . $platform->name
        ]);

        $coupon = $this->itemService->findByRefAndPlatform('#0001', $this->idPlatform);

        $total_amount = $unit_price = 0;
        $note = [];
        foreach ($cpns as $couponItem) {
            $unit_price += $couponItem['value'];
            $total_amount += $couponItem['value'];
            $note[] = $couponItem['sn'];
        }

        $order->orderDetails()->create([
            'qty' => 1,
            'unit_price' => $unit_price,
            'total_amount' => $total_amount,
            'note' => implode(",", $note),
            'item_id' => $coupon->id,
        ]);

        DB::beginTransaction();
        try {
            $order->updateStatus(OrderEnum::Ready);
            $simulation = Ordering::simulate($order);
            if ($simulation) {
                $status = Ordering::run($simulation);
                if ($status->value == OrderEnum::Failed->value) {
                    DB::commit();
                    return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Coupons order failed'));
                }
            } else {
                $order->updateStatus(OrderEnum::Failed);
                DB::commit();
                return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Coupons order failed'));
            }

            $this->coupons = [];
            foreach ($note as $sn) {
                $coupon = $this->couponService->getBySn($sn);
                if (!$coupon->consumed) {
                    $this->couponService->updateCoupon($coupon, [
                        'user_id' => auth()->user()->id,
                        'purchase_date' => now(),
                        'status' => CouponStatusEnum::purchased->value
                    ]);
                }
                $this->coupons[] = $coupon;
            }

            $this->displayedAmount = $total_amount;
            $this->buyed = true;
            $this->linkOrder = route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id]);
            $this->order = $order;
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $order->updateStatus(OrderEnum::Failed);
            Log::error($exception->getMessage());
        }
    }

    public function getCouponsForAmount($amount): array
    {
        $availableCoupons = $this->couponService->getAvailableCouponsForPlatform(
            $this->idPlatform,
            auth()->user()->id
        );

        $selectedCoupons = [];
        $total = 0;
        $lastValue = 0;

        if ($availableCoupons->count() == 0) {
            $lastValue = 0;
        }

        foreach ($availableCoupons as $coupon) {
            $lastValue = $coupon->value;
            if ($total + $coupon->value <= $amount) {
                $this->couponService->updateCoupon($coupon, [
                    'status' => CouponStatusEnum::reserved->value,
                    'user_id' => auth()->user()->id,
                    'reserved_until' => now()->addMinutes($this->time)
                ]);
                $selectedCoupons[] = $coupon;
                $total += $coupon->value;
            }
        }

        return [
            'amount' => $total,
            'coupons' => $selectedCoupons,
            'lastValue' => $this->equal ? 0 : $lastValue,
        ];
    }

    public function render()
    {
        $params = ['platform' => $this->platformService->getPlatformById($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
