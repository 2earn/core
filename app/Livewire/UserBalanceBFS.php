<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use App\Services\Balances\BalanceService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
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
        try {
            $balanceService = app(BalanceService::class);
            $response = $balanceService->getPurchaseBFSUserDatatables(Auth()->user()->idUser, $this->type);

            // Get the data from the datatables response
            $data = json_decode($response->getContent(), true);

            return [
                'data' => $data['data'] ?? [],
                'total' => $data['recordsTotal'] ?? 0,
            ];
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
