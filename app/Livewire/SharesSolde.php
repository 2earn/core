<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
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
        $locale = app()->getLocale();
        $token = generateUserToken();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get(route('api_shares_solde', [
                'locale' => $locale,
            ]), [
                'start' => ($this->getPage() - 1) * $this->perPage,
                'length' => $this->perPage,
            ]);

            if ($response->successful()) {
                $payload = $response->json();
                return [
                    'data' => $payload['data'] ?? [],
                    'total' => $payload['recordsTotal'] ?? ($payload['recordsFiltered'] ?? 0),
                ];
            }
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
