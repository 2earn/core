<?php

namespace App\Http\Livewire;

use App\Models\Order;
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['orders'] = Order::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['orders'] = Order::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.orders', $params)->extends('layouts.master')->section('content');
    }
}
