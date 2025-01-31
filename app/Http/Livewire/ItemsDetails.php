<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ItemsDetails extends Component
{
    public $idItem;

    public function mount($id)
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->idItem = $id;
    }

    public function render()
    {
        $item = Item::find($this->idItem);
        if (is_null($item)) {
            $this->redirect()->route('items_index', ['locale' => app()->getLocale()]);
        }
        $itemId = $this->idItem;

        $sumOfItemIds = OrderDetail::where('item_id', $itemId)
            ->whereHas('order', function ($query) {
                $query->where('status', OrderEnum::Paid->value);
            })->sum('qty');
        $params = [
            'item' => $item,
            'sumOfItemIds' => $sumOfItemIds,
        ];
        return view('livewire.items-details', $params)->extends('layouts.master')->section('content');

    }
}
