<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrdersReview extends Component
{
    public $orders = [];
    public $orderIds = '';
    public $currentRouteName;

    public function mount($orderIds = '')
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->orderIds = $orderIds;

        if ($orderIds) {
            $ids = explode(',', $orderIds);
            $this->orders = Order::with(['orderDetails.item.deal.platform', 'platform', 'user'])
                ->whereIn('id', $ids)
                ->where('user_id', auth()->user()->id)
                ->where('status', OrderEnum::Ready)
                ->get();
        }
    }

    public function simulateOrder($orderId)
    {
        $order = Order::find($orderId);

        if (!$order || $order->user_id !== auth()->user()->id) {
            session()->flash('error', trans('Order not found'));
            return;
        }

        $simulation = Ordering::simulate($order);

        if ($simulation) {
            $status = Ordering::run($simulation);

            if ($status->value == OrderEnum::Failed->value) {
                session()->flash('error', trans('Order') . ' #' . $order->id . ' ' . trans('failed'));
            } else {
                session()->flash('success', trans('Order') . ' #' . $order->id . ' ' . trans('processed successfully'));
            }
        } else {
            $order->updateStatus(OrderEnum::Failed);
            session()->flash('error', trans('Order') . ' #' . $order->id . ' ' . trans('simulation failed'));
        }

        // Refresh orders
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
                    $status = Ordering::run($simulation);

                    if ($status->value == OrderEnum::Failed->value) {
                        $failedCount++;
                    } else {
                        $successCount++;
                    }
                } else {
                    $order->updateStatus(OrderEnum::Failed);
                    $failedCount++;
                }
            }
        }

        if ($successCount > 0) {
            session()->flash('success', $successCount . ' ' . trans('orders processed successfully'));
        }

        if ($failedCount > 0) {
            session()->flash('error', $failedCount . ' ' . trans('orders failed'));
        }

        // Refresh orders
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

