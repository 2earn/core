<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Services\Orders\OrderingSimulation;
use Illuminate\Support\Facades\Lang;
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
        if (!is_null($this->search) && !empty($this->search)) {
            $params['orders'] = Order::where('user_id',auth()->user()->id)->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['orders'] = Order::where('user_id',auth()->user()->id)->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.orders-previous', $params)->extends('layouts.master')->section('content');
    }
    }
