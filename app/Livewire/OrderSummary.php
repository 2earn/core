<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrderSummary extends Component
{
    public $cart;
    public $orders = [];
    public $currentRouteName;

    public $listeners = [
        'validateCart' => 'validateCart',
        'clearCart' => 'clearCart'
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function createAndSimulateOrder()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $platformId = null;
        $ordersData = [];
        foreach ($cart->cartItem()->get() as $cartItem) {
            $item = $cartItem->item()->first();
            $platformId = $item->deal()->first()->platform_id;
            if (!$platformId) {
                $item->platform_id;
            }
            $ordersData[$platformId][] = $cartItem;
        }

        $order = Order::create(['user_id' => auth()->user()->id, 'platform_id' => $platformId, 'note' => 'Product buy platform']);
        foreach ($ordersData as $ordersDataItems) {

            foreach ($ordersDataItems as $ordersItems) {
                $order->orderDetails()->create([
                    'qty' => $ordersItems->qty,
                    'unit_price' => $ordersItems->unit_price,
                    'total_amount' => $ordersItems->total_amount,
                    'item_id' => $ordersItems->item_id,
                    'shipping' => $ordersItems->shipping,
                ]);
            }
            $order->updateStatus(OrderEnum::Ready);

        }

        return redirect()->route('orders_simulation', ['locale' => app()->getLocale(), 'id' => $order->id])->with('danger', trans('order failed'));

    }

    public function validateCart()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $ordersData = [];
        $orders = [];
        foreach ($cart->cartItem()->get() as $cartItem) {
            $item = $cartItem->item()->first();
            $platformId = $item->deal()->first()->platform_id;
            if (!$platformId) {
                $item->platform_id;
            }
            $ordersData[$platformId][] = $cartItem;
        }

        foreach ($ordersData as $key => $ordersDataItems) {

            $order = Order::create(['user_id' => auth()->user()->id,'platform_id' => $platformId,  'note' => 'Product buy platform ' . $key]);

            foreach ($ordersDataItems as $ordersItems) {
                $order->orderDetails()->create([
                    'qty' => $ordersItems->qty,
                    'unit_price' => $ordersItems->unit_price,
                    'total_amount' => $ordersItems->total_amount,
                    'item_id' => $ordersItems->item_id,
                    'shipping' => $ordersItems->shipping,
                ]);
            }
            $order->updateStatus(OrderEnum::Ready);
            $simulation = Ordering::simulate($order);

            if ($simulation) {
                $status = Ordering::run($simulation);
                if ($status->value == OrderEnum::Failed->value) {
                    return redirect()->route('orders_summary', ['locale' => app()->getLocale()])->with('danger', trans('order failed'));
                }
            } else {
                $order->updateStatus(OrderEnum::Failed);
                return redirect()->route('orders_summary', ['locale' => app()->getLocale()])->with('danger', trans('order failed'));
            }
            $this->orders[] = $order;
        }
        $this->clearCart();
    }

    public function clearCart()
    {
        Carts::initCart();
        $this->dispatch('update-cart');
    }

    public function getUniquePlatformsCount()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        if (!$cart) {
            return 0;
        }

        $platformIds = [];
        foreach ($cart->cartItem()->get() as $cartItem) {
            $item = $cartItem->item()->first();
            if ($item && $item->deal()->first()) {
                $platformId = $item->deal()->first()->platform_id;
            } else {
                $platformId = $item->platform_id ?? null;
            }

            if ($platformId && !in_array($platformId, $platformIds)) {
                $platformIds[] = $platformId;
            }
        }

        return count($platformIds);
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        $uniquePlatformsCount = $this->getUniquePlatformsCount();
        return view('livewire.order-summary', [
            'uniquePlatformsCount' => $uniquePlatformsCount
        ])->extends('layouts.master')->section('content');
    }
}
