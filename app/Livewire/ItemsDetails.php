<?php

namespace App\Livewire;

use App\Services\Items\ItemService;
use App\Services\OrderDetailService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ItemsDetails extends Component
{
    protected ItemService $itemService;
    protected OrderDetailService $orderDetailService;

    public $idItem;
    public $listeners = ['delete' => 'delete'];

    public function boot(ItemService $itemService, OrderDetailService $orderDetailService)
    {
        $this->itemService = $itemService;
        $this->orderDetailService = $orderDetailService;
    }

    public function mount($id)
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->idItem = $id;
    }

    public function delete($id)
    {
        try {
            $item = $this->itemService->findItemOrFail($id);
            $item->delete();

            return redirect()->route('items_index', ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Item Deleted Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('items_index', ['locale' => app()->getLocale()])
                ->with('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        $item = $this->itemService->findItem($this->idItem);

        if (is_null($item)) {
            return $this->redirect()->route('items_index', ['locale' => app()->getLocale()]);
        }

        $sumOfItemIds = $this->orderDetailService->getSumOfPaidItemQuantities($this->idItem);

        $params = [
            'item' => $item,
            'sumOfItemIds' => $sumOfItemIds,
        ];

        return view('livewire.items-details', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}
