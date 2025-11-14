<?php

namespace App\Livewire;

use App\Services\BalanceService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class SharesSolde extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Fetch shares data with server-side pagination parameters.
     * @return array{data: array<int, array>, total: int}
     */
    public function getSharesData(): array
    {
        try {
            $balanceService = app(BalanceService::class);
            $response = $balanceService->getSharesSoldeDatatables(auth()->user()->idUser);

            $data = json_decode($response->getContent(), true);

            return [
                'data' => $data['data'] ?? [],
                'total' => $data['recordsTotal'] ?? ($data['recordsFiltered'] ?? 0),
            ];
        } catch (\Throwable $e) {
            Log::error('Error fetching shares list: ' . $e->getMessage());
        }

        return [
            'data' => [],
            'total' => 0,
        ];
    }

    public function render()
    {
        $result = $this->getSharesData();

        $transactions = new LengthAwarePaginator(
            $result['data'],
            $result['total'],
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.shares-solde', [
            'transactions' => $transactions,
        ])->extends('layouts.master')->section('content');
    }
}
