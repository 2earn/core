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
    public $displayedAmount;
    public $coupons;
    public $equal = false;
    public $simulated = false;
    public $buyed = false;
    public $linkOrder = null;
    public $lastValue;
    public $idPlatform;

    public $listeners = [
        'simulateCoupon' => 'simulateCoupon',
        'BuyCoupon' => 'BuyCoupon',
        'ConfirmPurchase' => 'ConfirmPurchase',
        'CancelPurchase' => 'CancelPurchase',
        'consumeCoupon' => 'consumeCoupon'
    ];

    public function mount()
    {
        $this->idPlatform = Route::current()->parameter('id');;
        $this->amount = 0;
        $this->displayedAmount = 0;
    }


    public function updatedAmount($value)
    {
        $this->coupons = [];
        $this->simulated=false;
    }

    public function consumeCoupon()
    {

    }

    public function CancelPurchase()
    {
        $this->redirect(route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform]));
    }

    public function ConfirmPurchase()
    {
        $this->amount = $this->amount + $this->lastValue;
        $this->lastValue = 0;
        $this->simulateCoupon();
        $this->BuyCoupon();
    }

    public function simulateCoupon()
    {
        $this->amount = $this->displayedAmount;
        $preSumulationResult = $this->getCouponsForAmount($this->amount);

        if ($preSumulationResult['amount'] == $this->displayedAmount) {
            $this->equal = true;
        } else {
            $this->equal = false;
        }
        if (is_null($preSumulationResult)) {
            return redirect()->route('coupon_buy', ['locale' => app()->getLocale(), 'id' => $this->idPlatform])->with('danger', trans('Amount simulation failed'));
        }
        $result = $this->getCouponsForAmount($preSumulationResult['lastValue'] + $this->amount);
        if ($this->equal) {
            $this->lastValue = $preSumulationResult['lastValue'];
            $this->amount = $preSumulationResult['amount'];
            $this->coupons = $preSumulationResult['coupons'];
        } else {
            $this->lastValue = $preSumulationResult['lastValue'];
            $this->amount = $preSumulationResult['amount'];
            $this->coupons = $result['coupons'];
        }

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
            if (!$coupon->consumed) {
                $coupon->update([
                    'user_id' => auth()->user()->id,
                    'purchase_date' => now(),
                    'status' => CouponStatusEnum::sold->value
                ]);
            }
        }
        $this->buyed = true;
        $this->linkOrder = route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id]);
    }

    public function getCouponsForAmount($amount)
    {
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
            $lastValue = $coupon->value;
            if ($total + $coupon->value <= $amount) {
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
