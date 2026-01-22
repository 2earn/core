<?php

namespace App\Livewire;

use App\Services\Orders\OrderService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPrevious extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $disableNote = '';

    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    protected OrderService $orderService;

    public function boot(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function mount()
    {
        $this->page = request()->query('page', 1);
        $this->currentRouteName = Route::currentRouteName();
    }


    public function updatingSearch(): void
    {
        $this->resetPage();
    }


    public function render()
    {
        $params['orders'] = $this->orderService->getUserOrdersPaginated(
            auth()->user()->id,
            self::PAGE_SIZE,
            $this->search
        );
        return view('livewire.orders-previous', $params)->extends('layouts.master')->section('content');
    }
}
