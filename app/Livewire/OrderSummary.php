<?php

namespace App\Livewire;

use App\Models\Cart;

use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Livewire\Component;
use Illuminate\Support\Facades\Route;

class OrderSummary extends Component
{
    public $cart;
    public $orders = [];

    public $listeners = [
        'validateCart' => 'validateCart',
        'clearCart' => 'clearCart'
    ];
    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
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
                    'item_id' => $ordersItems->id,
                    'shipping' => $ordersItems->shipping,
                ]);
            }
            $order->updateStatus(OrderEnum::Ready);
            $simulation = Ordering::simulate($order);
            if ($simulation) {
                Ordering::run($simulation);
            }
            $this->orders[] = $order;
        }
        $this->clearCart();
    }

    public function clearCart()
    {
        Carts::initCart();
        $this->dispatch('itemAddedToCart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.order-summary')->extends('layouts.master')->section('content');
    }
}
