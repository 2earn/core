<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Item;
use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Lang;
use Core\Enum\OrderEnum;
use Livewire\Component;

class OrderSummary extends Component
{
    public $cart;

    public $listeners = [
        'validateCart' => 'validateCart',
        'clearCart' => 'clearCart'
    ];

    public function validateCart()
    {
        $order = Order::create(['user_id' => auth()->user()->id, 'note' => 'Product buy ']);
        $cart = Cart::where('user_id', auth()->user()->id)->first();

        foreach ($cart->cartItem()->get() as $cartItem) {
            $item = $cartItem->item()->first();
            dd($item->deal()->first()->platform_id);
            $order->orderDetails()->create([
                'qty' => $item->qty,
                'unit_price' => $item->unit_price,
                'total_amount' => $item->total_amount,
                'item_id' => $item->id,
            ]);
        }

        $order->updateStatus(OrderEnum::Ready);
        $simulation = Ordering::simulate($order);
        if ($simulation) {
            Ordering::run($simulation);
        }
        return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $order->id])->with('success', Lang::get('Status update succeeded'));
    }

    public function clearCart()
    {
        Carts::initCart();
        $this->emit('itemAddedToCart');
    }

    public function render()
    {
        $this->cart = Carts::getOrCreateCart();
        return view('livewire.order-summary')->extends('layouts.master')->section('content');
    }
}
