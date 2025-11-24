<?php

namespace App\Livewire;

use App\Services\Items\ItemService;
use Illuminate\Support\Facades\Log;
use Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class ItemsIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    public $listeners = ['delete' => 'delete'];

    protected ItemService $itemService;

    public function boot(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function delete($id)
    {
        try {
            $this->itemService->deleteItem($id);
            return redirect()->route('items_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Item Deleted Successfully'));
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('items_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }


    public function render()
    {
        $params['items'] = $this->itemService->getItems($this->search, self::PAGE_SIZE);
        return view('livewire.items-index', $params)->extends('layouts.master')->section('content');
    }
}
