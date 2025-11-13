<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UserBalanceCB extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $userTransaction = null;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
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
        $locale = app()->getLocale();
        $idAmounts = 'cash-Balance';
        $token = generateUserToken();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get(route('api_user_balances', [
                'locale' => $locale,
                'idAmounts' => $idAmounts
            ]), [
                'start' => ($this->getPage() - 1) * $this->perPage,
                'length' => $this->perPage,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'data' => $data['data'] ?? [],
                    'total' => $data['recordsTotal'] ?? 0,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching balances: ' . $e->getMessage());
        }

        return [
            'data' => [],
            'total' => 0,
        ];
    }

    public function render()
    {
        $result = $this->getTransactionsData();

        // Create paginator instance
        $transactions = new LengthAwarePaginator(
            $result['data'],
            $result['total'],
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.user-balance-c-b', [
            'transactions' => $transactions,
            'total' => $result['total'],
            'usdRate' => usdToSar() ?? 0,
            'paytabsRoute' => route('paytabs', app()->getLocale()),
        ])->extends('layouts.master')->section('content');
    }
}
