<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class OrderItem extends Component
{
    const CURRENCY = '$';
    public $idOrder;

    public function mount(Request $request)
    {
        $this->idOrder = Route::current()->parameter('id');
        $this->currentRouteName = Route::currentRouteName();
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
