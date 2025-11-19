<?php

namespace App\Livewire;

use App\Services\Balances\BalanceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceCB extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $userTransaction = null;

    protected $paginationTheme = 'bootstrap';
    protected $balanceService;

    public function mount(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
        $currentUserId = optional(Auth::user())->idUser ?? null;
        try {
            $this->userTransaction = getUsertransaction($currentUserId);
        } catch (\Throwable $e) {
            $this->userTransaction = null;
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getTransactionsData()
    {

        try {
            $balanceService = app(BalanceService::class);
            $response = $balanceService->getUserBalancesDatatables('cash-Balance');
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
        $params = [
            'transactions' => new LengthAwarePaginator(
                $result['data'],
                $result['total'],
                $this->perPage,
                $this->getPage(),
                ['path' => request()->url(), 'pageName' => 'page']
            ),
            'total' => $result['total'],
            'usdRate' => usdToSar() ?? 0,
            'paytabsRoute' => route('paytabs', app()->getLocale()),
        ];
        return view('livewire.user-balance-c-b', $params)->extends('layouts.master')->section('content');
    }
}
