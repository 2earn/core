<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceBFS extends Component
{
    use WithPagination;

    public $bfss = [];
    public $type;
    public $totalBfs;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount(Request $request)
    {
        $this->type = $request->input('type');
        if (is_null($this->type)) {
            $this->type = "ALL";
        }

        $this->bfss = Balances::getStoredUserBalances(Auth()->user()->idUser, Balances::BFSS_BALANCE);
        $balances = Balances::getStoredUserBalances(Auth()->user()->idUser);
        $this->totalBfs = Balances::getTotalBfs($balances);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getTransactionsData()
    {
        $locale = app()->getLocale();
        $token = generateUserToken();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get(route('api_user_bfs_purchase', [
                'locale' => $locale,
                'type' => $this->type
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
            Log::error('Error fetching BFS balances: ' . $e->getMessage());
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

        return view('livewire.user-balance-b-f-s', [
            'transactions' => $transactions,
            'total' => $result['total'],
        ])->extends('layouts.master')->section('content');
    }
}
