<?php

namespace App\Livewire;

use App\Services\Balances\BalanceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceDB extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getTransactionsData(): array
    {
        try {
            $balanceService = app(BalanceService::class);

            $response = $balanceService->getUserBalancesDatatables('Discounts-Balance');
            $data = json_decode($response->getContent(), TRUE);
            return ['data' => $data['data'] ?? [], 'total' => $data['recordsTotal'] ?? 0,];
        } catch (\Exception $e) {
            Log::error('Error fetching balances: ' . $e->getMessage());
        }

        return ['data' => [], 'total' => 0];
    }

    public function render()
    {
        $result = $this->getTransactionsData();

        $transactions = new LengthAwarePaginator(
            $result['data'],
            $result['total'],
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.user-balance-d-b', [
            'transactions' => $transactions,
        ])->extends('layouts.master')->section('content');
    }
}
