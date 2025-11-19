<?php

namespace App\Livewire;

use App\Services\Balances\BalanceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceChance extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getTransactionsData()
    {
        try {
            $balanceService = app(BalanceService::class);
            $response = $balanceService->getChanceUserDatatables(auth()->user()->idUser);
            $data = json_decode($response->getContent(), true);

            return [
                'data' => $data['data'] ?? [],
                'total' => $data['recordsTotal'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching Chance balances: ' . $e->getMessage());
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

        return view('livewire.user-balance-chance', [
            'transactions' => $transactions,
            'total' => $result['total'],
        ])->extends('layouts.master')->section('content');
    }
}
