<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Services\Items\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemsCreateUpdate extends Component
{
    use WithFileUploads;

    public $idItem;
    public $dealId, $deal_id;
    public $platformId;
    public $deals = [];
    public $thumbnailsImage;

    public $name, $ref, $price, $discount, $discount_2earn, $photo_link, $description, $stock;

    public $update = false;

    protected ItemService $itemService;

    public function boot(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    protected $rules = [
        'name' => 'required',
        'ref' => 'required',
        'price' => 'required',
        'discount' => 'required',
        'discount_2earn' => 'required',
        'thumbnailsImage' => 'nullable|image|mimes:jpeg,png,jpg',
    ];

    public function mount(Request $request)
    {
        $this->idItem = $request->input('id');
        $this->dealId = $request->input('dealId');
        $this->platformId = Route::current()->parameter('platformId');
        if (!is_null($this->platformId)) {
            $this->deals = Deal::where('platform_id', $this->platformId)->get();
        }

        if (!is_null($this->dealId)) {
            $deal = Deal::find($this->dealId);
            if (!is_null($deal)) {
                $this->platformId = $deal->platform_id;
            }
        }
        if (!is_null($this->idItem)) {
            $this->edit($this->idItem);
        }
    }

    public function edit($idItem)
    {
        $item = $this->itemService->findItemOrFail($idItem);
        $this->idItem = $idItem;
        $this->name = $item->name;
        $this->ref = $item->ref;
        $this->price = $item->price;
        $this->discount = $item->discount;
        $this->discount_2earn = $item->discount_2earn;
        $this->photo_link = $item->photo_link;
        $this->description = $item->description;
        $this->stock = $item->stock;
        $this->platform_id = $item->platform_id;
        $this->deal_id = $item->deal_id;
        $this->update = true;
        $this->deals = Deal::where('platform_id', $this->platform_id)->get();
    }

    public function cancel()
    {
        return redirect()->route('items_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Item operation canceled'));
    }

    public function updateI()
    {
        $this->validate();

        try {
            $dataItem = [
                'name' => $this->name,
                'ref' => $this->ref,
                'price' => $this->price,
                'discount' => $this->discount,
                'discount_2earn' => $this->discount_2earn,
                'photo_link' => $this->photo_link,
                'description' => $this->description,
                'stock' => $this->stock,
            ];

            if (!is_null($this->deal_id)) {
                $dataItem['deal_id'] = $this->deal_id;
            }

            $this->itemService->updateItem($this->idItem, $dataItem);
            $item = $this->itemService->findItemOrFail($this->idItem);

            if ($this->thumbnailsImage) {
                $this->itemService->handleImageUpload($item, $this->thumbnailsImage);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('items_detail', ['locale' => app()->getLocale(), 'id' => $this->idItem])->with('danger', Lang::get('Something goes wrong while updating Item'));
        }
        return redirect()->route('items_detail', ['locale' => app()->getLocale(), 'id' => $this->idItem])->with('success', Lang::get('Item Updated Successfully'));

    }

    public function store()
    {
        $item = null;
        $this->validate();
        $itemData = [
            'name' => $this->name,
            'ref' => $this->ref,
            'price' => $this->price,
            'discount' => $this->discount,
            'discount_2earn' => $this->discount_2earn,
            'photo_link' => $this->photo_link,
            'description' => $this->description,
            'stock' => $this->stock,
            'deal_id' => $this->dealId,
            'platform_id' => $this->platformId,
        ];

        try {
            $item = $this->itemService->createItem($itemData);

            if ($this->thumbnailsImage) {
                $this->itemService->handleImageUpload($item, $this->thumbnailsImage);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('items_detail', ['locale' => app()->getLocale(), 'id' => $item->id])->with('danger', Lang::get('Something goes wrong while creating Faq'));
        }
        return redirect()->route('items_detail', ['locale' => app()->getLocale(), 'id' => $item->id])->with('success', Lang::get('Item Created Successfully'));
    }

    public function render()
    {
        $params = ['item' => null];
        if (!is_null($this->idItem)) {
            $params = ['item' => $this->itemService->findItem($this->idItem),];
        }
        return view('livewire.items-create-update', $params)->extends('layouts.master')->section('content');
    }
}
