<?php

namespace App\Livewire;

use App\Services\Orders\OrderService;
use App\Services\Orders\OrderingSimulation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $disableNote = '';

    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'simulateOrderCreation' => 'simulateOrderCreation',
        'validateOrderCreation' => 'validateOrderCreation'
    ];

    protected OrderService $orderService;

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->page = request()->query('page', 1);
        $this->currentRouteName = Route::currentRouteName();
    }

    public function validateOrderCreation($orderId)
    {
        $status = OrderingSimulation::validate($orderId);
        if ($status) {
            return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $orderId])->with('success', Lang::get('Status update succeeded') . ' : ' . Lang::get($status));
        } else {
            return redirect()->route('orders_index', ['locale' => app()->getLocale(), 'page' => $this->page])->with('warning', Lang::get('Status update failed') . ' : ' . Lang::get($status));
        }
    }
    public function simulateOrderCreation()
    {
        OrderingSimulation::simulate();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getPendingOrdersCount()
    {
        return $this->orderService->getPendingOrdersCount(
            auth()->user()->id,
            [\Core\Enum\OrderEnum::Ready, \Core\Enum\OrderEnum::Simulated]
        );
    }

    public function goToOrdersReview()
    {
        $orderIds = $this->orderService->getPendingOrderIds(
            auth()->user()->id,
            [\Core\Enum\OrderEnum::Ready, \Core\Enum\OrderEnum::Simulated]
        );

        if (empty($orderIds)) {
            session()->flash('info', trans('No pending orders to review'));
            return;
        }

        return redirect()->route('orders_review', [
            'locale' => app()->getLocale(),
            'orderIds' => implode(',', $orderIds)
        ]);
    }

    public function render()
    {
        $params['orders'] = $this->orderService->getAllOrdersPaginated(self::PAGE_SIZE);
        $params['pendingOrdersCount'] = $this->getPendingOrdersCount();
        return view('livewire.orders-index', $params)->extends('layouts.master')->section('content');
    }
}
