<?php

namespace App\Livewire;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\CommissionBreakDown;
use App\Models\DiscountBalances;
use App\Models\Order;
use App\Models\OrderDeal;
use App\Services\Orders\OrderingSimulation;
use Core\Enum\CommissionTypeEnum;
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
        $params = [
            'order' => Order::find($this->idOrder),
            'discount' => null,
            'bfss' => null,
            'cash' => null,
        ];

        if (!is_null($this->idOrder)) {
            if ($params['order']) {
                $params['discount'] = DiscountBalances::where('order_id', $params['order']->id)->first();
                $params['bfss'] = BFSsBalances::where('order_id', $params['order']->id)->exists() ? BFSsBalances::where('order_id', $params['order']->id)->get() : null;
                $params['cash'] = CashBalances::where('order_id', $params['order']->id)->first();
                $params['orderDeals'] = OrderDeal::where('order_id', $params['order']->id)->get();
                $params['commissions'] = CommissionBreakDown::where('order_id', $params['order']->id)->whereNot('type', CommissionTypeEnum::RECOVERED)->get();
            }
            return view('livewire.order-item', $params)->extends('layouts.master')->section('content');
        }
    }
}
