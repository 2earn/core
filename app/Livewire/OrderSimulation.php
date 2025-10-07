<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrderSimulation extends Component
{
    public $idOrder;
    public $order;
    public $currentRouteName;
    public $simulation;
    public $validated;
    public $listeners = [
        'validateOrder' => 'validateOrder',
        'makeOrderReady' => 'makeOrderReady'
    ];

    public function mount()
    {
        $this->idOrder = Route::current()->parameter('id');
        $this->currentRouteName = Route::currentRouteName();
        $this->order = Order::findOrFail($this->idOrder);
        if (in_array($this->order->status->value, [OrderEnum::Simulated->value, OrderEnum::Ready->value])) {
            $this->simulation = Ordering::simulate($this->order);
        }
        $this->validated = false;
    }

    public function makeOrderReady()
    {
        $this->order = Order::findOrFail($this->idOrder);
        if ($this->order->status->value == OrderEnum::New->value && $this->order->orderDetails->count() > 0) {
            $this->order->updateStatus(OrderEnum::Ready);
        }
        return redirect()->route('orders_simulation', ['locale' => app()->getLocale(), 'id' =>  $this->order->id])->with('danger', trans('Empty order'));

    }

    public function validateOrder()
    {
        if (!$this->validated) {
            $status = Ordering::run($this->simulation);
            $this->clearCart();
            $this->order = Order::findOrFail($this->idOrder);
            if ($this->order->status->value == OrderEnum::Dispatched->value) {
                return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])->with('success', Lang::get('Ordering succeeded') . ' : ' . Lang::get($status->name));
            } else {
                return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])->with('warning', Lang::get('Ordering Failed') . ' : ' . Lang::get($status->name));
            }
        }
    }

    public function clearCart()
    {
        Carts::initCart();
        $this->dispatch('update-cart');
    }

    public function render()
    {
        $params = [];
        return view('livewire.order-simulation', $params)->extends('layouts.master')->section('content');
    }
}
