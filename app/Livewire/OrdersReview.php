<?php

namespace App\Livewire;

use App\Services\Orders\OrderService;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrdersReview extends Component
{
    public $orders = [];
    public $orderIds = '';
    public $currentRouteName;

    protected OrderService $orderService;

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount($orderIds = '')
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->orderIds = $orderIds;

        if ($orderIds) {
            $ids = explode(',', $orderIds);
            $this->orders = $this->orderService->getOrdersByIdsForUser(
                auth()->user()->id,
                $ids,
                [OrderEnum::Ready, OrderEnum::Simulated, OrderEnum::Failed]
            );
        }
    }

    public function simulateOrder($orderId)
    {
        $order = $this->orderService->findOrderForUser($orderId, auth()->user()->id);

        if (!$order) {
            session()->flash('error', trans('Order not found'));
            return;
        }

        $simulation = Ordering::simulate($order);

        if ($simulation) {
            session()->flash('success', trans('Order') . ' #' . $order->id . ' ' . trans('simulated successfully'));
        } else {
            $order->updateStatus(OrderEnum::Failed);
            session()->flash('error', trans('Order') . ' #' . $order->id . ' ' . trans('simulation failed'));
        }

        $this->mount($this->orderIds);
    }

    public function simulateAllOrders()
    {
        $successCount = 0;
        $failedCount = 0;

        foreach ($this->orders as $order) {
            if ($order->status == OrderEnum::Ready) {
                $simulation = Ordering::simulate($order);

                if ($simulation) {
                    $successCount++;
                } else {
                    $order->updateStatus(OrderEnum::Failed);
                    $failedCount++;
                }
            }
        }

        if ($successCount > 0) {
            session()->flash('success', $successCount . ' ' . trans('orders simulated successfully'));
        }

        if ($failedCount > 0) {
            session()->flash('error', $failedCount . ' ' . trans('orders simulation failed'));
        }

        $this->mount($this->orderIds);
    }

    public function goToOrdersList()
    {
        return redirect()->route('orders_index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.orders-review')->extends('layouts.master')->section('content');
    }
}

