<?php

namespace App\Livewire;

use App\Models\Order;
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


    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['orders'] = Order::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['orders'] = Order::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.orders-index', $params)->extends('layouts.master')->section('content');
    }
}
