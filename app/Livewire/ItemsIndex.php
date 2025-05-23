<?php

namespace App\Livewire;

use App\Models\Item;
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

    public function delete($id)
    {
        try {
            Item::findOrFail($id)->delete();
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
        if (!is_null($this->search) && !empty($this->search)) {
            $params['items'] = Item::where('name', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['items'] = Item::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.items-index', $params)->extends('layouts.master')->section('content');
    }
}
