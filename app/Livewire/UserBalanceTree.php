<?php

namespace App\Livewire;

use App\Services\Balances\BalanceTreeService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceTree extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Fetch Tree Balance transactions with pagination parameters.
     * @return array{data: array<int, array>, total: int}
     */
    public function getTransactionsData(): array
    {

        try {

            $balanceService = app(BalanceTreeService::class);
            $response = $balanceService->getTreeUserDatatables(auth()->user()->idUser);
            $data = json_decode($response->getContent(), true);
            return [
                'data' => $data['data'] ?? [],
                'total' => $data['recordsTotal'] ?? ($data['recordsFiltered'] ?? 0),
            ];
        } catch (\Throwable $e) {
            Log::error('Error fetching Tree Balance transactions: ' . $e->getMessage());
        }

        return [
            'data' => [],
            'total' => 0,
        ];
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

        return view('livewire.user-balance-tree', [
            'transactions' => $transactions,
        ])->extends('layouts.master')->section('content');
    }
}
