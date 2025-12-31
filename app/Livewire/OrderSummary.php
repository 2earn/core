<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Services\Carts\Carts;
use App\Services\Orders\OrderService;
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

    protected OrderService $orderService;
    protected CartService $cartService;

    public function boot(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function createAndSimulateOrder()
    {
        if ($this->cartService->isCartEmpty(auth()->user()->id)) {
            return redirect()->route('orders_summary', ['locale' => app()->getLocale()])
                ->with('warning', trans('Cart is empty'));
        }

        $ordersData = $this->cartService->getCartItemsGroupedByPlatform(auth()->user()->id);

        $createdOrderIds = $this->orderService->createOrdersFromCartItems(
            auth()->user()->id,
            $ordersData,
            OrderEnum::Ready
        );

        $this->clearCart();

        return redirect()->route('orders_review', [
            'locale' => app()->getLocale(),
            'orderIds' => implode(',', $createdOrderIds)
        ])->with('success', trans('Orders created successfully'));
    }

    public function validateCart()
    {
        $ordersData = $this->cartService->getCartItemsGroupedByPlatform(auth()->user()->id);

        foreach ($ordersData as $platformId => $ordersDataItems) {
            $order = $this->orderService->createOrderWithDetails(
                auth()->user()->id,
                $platformId,
                $ordersDataItems,
                OrderEnum::Ready
            );

            $simulation = Ordering::simulate($order);

            if ($simulation) {
                $status = Ordering::run($simulation);
                if ($status->value == OrderEnum::Failed->value) {
                    return redirect()->route('orders_summary', ['locale' => app()->getLocale()])
                        ->with('danger', trans('order failed'));
                }
            } else {
                $order->updateStatus(OrderEnum::Failed);
                return redirect()->route('orders_summary', ['locale' => app()->getLocale()])
                    ->with('danger', trans('order failed'));
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
        return $this->cartService->getUniquePlatformsCount(auth()->user()->id);
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
