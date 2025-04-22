<?php

namespace App\Livewire;

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
    public $equal = false;
    public $simulated = false;
    public $lastValue;
    public $idPlatform;

    public $listeners = [
        'simulateCoupon' => 'simulateCoupon',
        'BuyCoupon' => 'BuyCoupon',
        'addLastValue' => 'addLastValue'
    ];

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');;
        $this->amount = 0;
    }


    public function updatedAmount($value)
    {
        $this->coupons = [];
    }

    public function addLastValue()
    {
        $this->amount = $this->amount + $this->lastValue->value;
        $this->simulateCoupon();
    }

    public function simulateCoupon()
    {
        $result = $this->getCouponsForAmount($this->amount);
        if (is_null($result)) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Amount simulation failed'));
        }

        $this->lastValue = $result['lastValue'];
        $this->amount = $result['amount'];
        $this->coupons = $result['coupons'];
        $this->simulated = true;
    }

    public function BuyCoupon()
    {
        $platform = Platform::find($this->idPlatform);
        $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Coupons buy from' . ' :' . $this->idPlatform . '-' . $platform->name]);
        $coupon = Item::where('ref', '#0001')->where('platform_id', $this->idPlatform)->first();

        $total_amount = $unit_price = $qty = 0;
        $note = [];
        foreach ($this->coupons as $couponItem) {
            $qty++;
            $unit_price += $couponItem['value'];
            $total_amount += $couponItem['value'];
            $note[] = $couponItem['sn'];
        }

        $order->orderDetails()->create([
            'qty' => $qty,
            'unit_price' => $unit_price,
            'total_amount' => $total_amount,
            'note' => implode(",", $note),
            'item_id' => $coupon->id,
        ]);


        $order->updateStatus(OrderEnum::Ready);
        $simulation = Ordering::simulate($order);

        if ($simulation) {
            Ordering::run($simulation);
        }
        foreach ($note as $sn) {
            $coupon = Coupon::where('sn', $sn)->first();
            $coupon->update([
                'user_id' => auth()->user()->id,
                'purchase_date' => now(),
                'status' => CouponStatusEnum::sold->value
            ]);
        }
        return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id])->with('success', Lang::get('Coupons buying succeeded'));
    }

    public function getCouponsForAmount($amount)
    {
        $this->equal = false;
        $availableCoupons = Coupon::where('status', CouponStatusEnum::available->value)
            ->where('platform_id', $this->idPlatform)
            ->orderBy('value', 'desc')
            ->get();
        $selectedCoupons = [];
        $total = 0;
        if ($availableCoupons->count() == 0) {
            $lastValue = 0;
        }
        foreach ($availableCoupons as $coupon) {
            $lastValue = $coupon;
            if ($total + $coupon->value <= $amount) {
                $selectedCoupons[] = $coupon;
                $total += $coupon->value;
            }

            if ($total == $amount) {
                $this->equal = true;
                break;
            }
        }
        return [
            'amount' => $total,
            'coupons' => $selectedCoupons,
            'lastValue' => $lastValue,
        ];
    }

    public function render()
    {
        $params = ['platform' => Platform::find($this->idPlatform)];
        return view('livewire.coupon-buy', $params)->extends('layouts.master')->section('content');
    }
}
