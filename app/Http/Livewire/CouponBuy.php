<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use App\Models\Item;
use App\Models\Order;
use App\Services\Orders\Ordering;
use Core\Enum\CouponStatusEnum;
use Core\Enum\OrderEnum;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CouponBuy extends Component
{
    public $amount;

    public $coupons;
    public $idPlatform;

    public $listeners = [
        'simulateCoupon' => 'simulateCoupon',
        'BuyCoupon' => 'BuyCoupon'
    ];

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');;
        $this->amount = 0;
    }


    public function simulateCoupon()
    {
        $result = $this->getCouponsForAmount($this->amount);
        if (is_null($result)) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Amount simulation failed'));
        }

        $this->amount = $result['amount'];
        $this->coupons = $result['coupons'];
    }

    public function BuyCoupon()
    {
        $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Coupon buy from :' . $this->idPlatform]);
        $coupon = Item::where('ref', '#0001')
            ->where('platform_id', $this->idPlatform)
            ->first();
        foreach ($this->coupons as $couponItem) {
            $order->orderDetails()->create([
                'qty' => 1,
                'unit_price' => $couponItem['value'],
                'total_amount' => $couponItem['value'],
                'note' => $couponItem['sn'],
                'item_id' => $coupon->id,
            ]);
        }

        $order->updateStatus(OrderEnum::Ready);
        $simulation = Ordering::simulate($order);

        if ($simulation) {
            Ordering::run($simulation);
        }
        foreach ($order->orderDetails()->get() as $key => $orderDetail) {
            $coupon = Coupon::where('sn', $orderDetail->note)->first();
            $coupon->update([
                'user_id' => auth()->user()->id,
                'purchase_date' => now(),
                'status' => CouponStatusEnum::sold->value
            ]);
        }
        return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id])->with('success', Lang::get('Status update succeeded'));
    }

    public function getCouponsForAmount($amount)
    {

        $availableCoupons = Coupon::where('status', CouponStatusEnum::available->value)
            ->where('platform_id', $this->idPlatform)
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
        return [
            'amount' => $total,
            'coupons' => $selectedCoupons,
        ];
    }

    public function render()
    {
        $params = ['platform' => Platform::find($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
