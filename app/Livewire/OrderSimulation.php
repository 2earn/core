<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Carts\Carts;
use App\Services\Orders\Ordering;
use App\Services\Orders\OrderService;
use App\Enums\OrderEnum;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrderSimulation extends Component
{
    protected OrderService $orderService;

    public $idOrder;
    public $order;
    public $currentRouteName;
    public $simulation;
    public $validated;
    public $listeners = [
        'validateOrder' => 'validateOrder',
        'makeOrderReady' => 'makeOrderReady'
    ];

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->idOrder = Route::current()->parameter('id');
        $this->currentRouteName = Route::currentRouteName();
        $this->order = Order::findOrFail($this->idOrder);
        if (in_array($this->order->status->value, [OrderEnum::Simulated->value, OrderEnum::Ready->value])) {
            $this->simulation =  Ordering::simulate($this->order);
        }
        $this->order->refresh();
        $this->validated = !is_null($this->order->payment_result)?$this->order->payment_result:false;;
    }

    public function makeOrderReady()
    {
        // Make order ready using service
        $result = $this->orderService->makeOrderReady($this->idOrder);

        if (!$result['success']) {
            return redirect()->route('orders_simulation', ['locale' => app()->getLocale(), 'id' => $this->idOrder])
                ->with('danger', trans($result['message']));
        }

        return redirect()->route('orders_simulation', ['locale' => app()->getLocale(), 'id' => $result['order']->id])
            ->with('success', trans($result['message']));
    }

    public function validateOrder()
    {
        // Validate order using service
        $result = $this->orderService->validateOrder($this->idOrder, $this->simulation, $this->validated);

        if (!$result['success']) {
            if (isset($result['shouldRedirect']) && !$result['shouldRedirect']) {
                return; // Already validated, do nothing
            }
            return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])
                ->with('danger', Lang::get($result['message']));
        }

        // Clear cart after validation
        $this->clearCart();

        $flashType = $result['isDispatched'] ? 'success' : 'warning';
        $message = Lang::get($result['message']) . ' : ' . Lang::get($result['orderStatus']->name);

        return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])
            ->with($flashType, $message);
    }

    public function cancelOrder()
    {
        $this->clearCart();

        // Cancel order using service
        $result = $this->orderService->cancelOrder($this->idOrder);

        $flashType = $result['success'] ? 'info' : 'danger';
        return redirect()->route('home', ['locale' => app()->getLocale()])
            ->with($flashType, Lang::get($result['message']));
    }

    public function clearCart()
    {
        Carts::initCart();
        $this->dispatch('update-cart');
    }

    public function render()
    {
        return view('livewire.order-simulation')->extends('layouts.master')->section('content');
    }
}
