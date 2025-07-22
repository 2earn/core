<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
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
        'validateOrder' => 'validateOrder'
    ];

    public function mount(Request $request)
    {
        $this->idOrder = Route::current()->parameter('id');
        $this->currentRouteName = Route::currentRouteName();
        $this->order = Order::findOrFail($this->idOrder);
        $this->simulation = Ordering::simulate($this->order);
        $this->validated = false;
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
