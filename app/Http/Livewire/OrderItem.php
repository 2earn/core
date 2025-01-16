<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Target;
use App\Services\Orders\OrderingSimulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrderItem extends Component
{
    const CURRENCY = '$';
    public $idOrder;
    public $currentRouteName;
    protected $listeners = [
        'validateOrderCreation' => 'validateOrderCreation'
    ];
    public function mount(Request $request)
    {
        $this->idOrder = Route::current()->parameter('id');
        $this->currentRouteName = Route::currentRouteName();
    }

    public function validateOrderCreation($orderId)
    {
        $status = OrderingSimulation::validate($orderId);
        if ($status) {
            return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])->with('success', Lang::get('Status update succeeded') . ' : ' . Lang::get($status));
        } else {
            return redirect()->route('orders_detail', ['locale' => app()->getLocale(), 'id' => $this->idOrder])->with('warning', Lang::get('Status update failed') . ' : ' . Lang::get($status));
        }
    }
    public function render()
    {
        $params = ['order' => Order::find($this->idOrder)];
        if (!is_null($this->idOrder)) {
            return view('livewire.order-item', $params)->extends('layouts.master')->section('content');

        }
        return view('livewire.order-item');
    }
}
