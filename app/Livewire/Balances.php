<?php

namespace App\Livewire;

use App\Services\Balances\BalanceOperationService;
use App\Models\Amount;
use App\Models\BalanceOperation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class Balances extends Component
{
    use WithPagination;
    public $operation;
    public $io;
    public $source;
    public $mode;
    public $amounts_id;
    public $note;
    public $modify_amount;
    public $search = '';
    public $perPage = 10;
    public $currentRouteName;
    protected BalanceOperationService $balanceOperationService;

    protected $paginationTheme = 'bootstrap';

    public function __construct()
    {
        $this->balanceOperationService = app(BalanceOperationService::class);
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $operations = $this->balanceOperationService->getFilteredOperations(
            $this->search,
            $this->perPage
        );

        return view('livewire.balances', [
            'operations' => $operations
        ])->extends('layouts.master')->section('content');
    }
}
