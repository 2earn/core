<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\Item;
use App\Models\Order;
use App\Services\Orders\Ordering;
use Core\Enum\CouponStatusEnum;
use Core\Enum\OrderEnum;
use Core\Models\Platform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CouponBuy extends Component
{
    const DELAY_FOR_COUPONS_SIMULATION = 5;
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


    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');
        $this->amount = 0;

        $this->maxAmount = Coupon::where(function ($query) {

            $query
                ->orWhere('status', CouponStatusEnum::available->value)
                ->orWhere(function ($subQueryReservedForOther) {
                    $subQueryReservedForOther->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '<', now());
                })
                ->orWhere(function ($subQueryReservedForUser) {
                    $subQueryReservedForUser->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '>=', now())
                        ->where('user_id', auth()->user()->id);
                });
        })
            ->where('platform_id', $this->idPlatform)
            ->sum('value');

        $this->time = getSettingIntegerParam('DELAY_FOR_COUPONS_SIMULATION', self::DELAY_FOR_COUPONS_SIMULATION);
    }


    public function consumeCoupon($id)
    {
        $couponToUpdate = Coupon::find($id);
        if (!$couponToUpdate->consumed) {
            $couponToUpdate->update([
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
            $coupon->update([
                'status' => CouponStatusEnum::available->value,
                'user_id', null
            ]);
        }
        foreach ($this->result['coupons'] as $coupon) {
            $coupon->update([
                'status' => CouponStatusEnum::available->value,
                'user_id', null
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
        $platform = Platform::find($this->idPlatform);
        $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Coupons buy from' . ' :' . $this->idPlatform . '-' . $platform->name]);
        $coupon = Item::where('ref', '#0001')->where('platform_id', $this->idPlatform)->first();

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
                $coupon = Coupon::where('sn', $sn)->first();
                if (!$coupon->consumed) {
                    $coupon->update([
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
        } catch (Exception $exception) {
            DB::rollBack();
            $order->updateStatus(OrderEnum::Failed);
            Log::error($exception->getMessage());
        }
    }

    public function getCouponsForAmount($amount): array
    {

        $availableCoupons = Coupon::where(function ($query) {

            $query
                ->orWhere('status', CouponStatusEnum::available->value)
                ->orWhere(function ($subQueryReservedForOther) {
                    $subQueryReservedForOther->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '<', now());
                })
                ->orWhere(function ($subQueryReservedForUser) {
                    $subQueryReservedForUser->where('status', CouponStatusEnum::reserved->value)
                        ->where('reserved_until', '>=', now())
                        ->where('user_id', auth()->user()->id);
                });
        })
            ->where('platform_id', $this->idPlatform)
            ->orderBy('value', 'desc')
            ->get();


        $selectedCoupons = [];
        $total = 0;

        if ($availableCoupons->count() == 0) {
            $lastValue = 0;
        }

        foreach ($availableCoupons as $coupon) {
            $lastValue = $coupon->value;
            if ($total + $coupon->value <= $amount) {
                $coupon->update([
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
        $params = ['platform' => Platform::find($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
