<?php

namespace App\Livewire;

use App\Services\BalanceService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UserBalanceSMS extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Fetch SMS transactions with pagination parameters.
     * @return array{data: array<int, array>, total: int}
     */
    public function getTransactionsData(): array
    {
        try {
            $balanceService = app(BalanceService::class);
            $response = $balanceService->getSmsUserDatatables(auth()->user()->idUser);

            $data = json_decode($response->getContent(), true);

            return [
                'data' => $data['data'] ?? [],
                'total' => $data['recordsTotal'] ?? ($data['recordsFiltered'] ?? (is_countable($data['data'] ?? null) ? count($data['data']) : 0)),
            ];
        } catch (\Throwable $e) {
            Log::error('Error fetching SMS balance transactions: ' . $e->getMessage());
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

        return view('livewire.user-balance-s-m-s', [
            'transactions' => $transactions,
        ])->extends('layouts.master')->section('content');
    }
}
