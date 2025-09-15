<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
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
        $ordersData = [];
        foreach ($cart->cartItem()->get() as $cartItem) {
            $item = $cartItem->item()->first();
            $ordersData[$item->deal()->first()->platform_id][] = $cartItem;
        }
        foreach ($ordersData as $key => $ordersDataItems) {

            $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Product buy platform ' . $key]);

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
            $ordersData[$item->deal()->first()->platform_id][] = $cartItem;
        }
        foreach ($ordersData as $key => $ordersDataItems) {

            $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Product buy platform ' . $key]);

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

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.order-summary')->extends('layouts.master')->section('content');
    }
}
