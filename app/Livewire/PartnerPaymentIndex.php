<?php

namespace App\Livewire;

use App\Models\PartnerPayment;
use App\Services\PartnerPayment\PartnerPaymentService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerPaymentIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 10;

    public $search = '';
    public $statusFilter = 'all';
    public $methodFilter = '';
    public $partnerFilter = '';
    public $fromDate = '';
    public $toDate = '';
    public $page;
    public $currentRouteName;

    protected $paginationTheme = 'bootstrap';
    protected $partnerPaymentService;

    public function boot(PartnerPaymentService $partnerPaymentService)
    {
        $this->partnerPaymentService = $partnerPaymentService;
    }

    protected $listeners = [
        'deletePartnerPayment' => 'deletePartnerPayment',
        'refreshList' => '$refresh'
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMethodFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->methodFilter = '';
        $this->partnerFilter = '';
        $this->fromDate = '';
        $this->toDate = '';
        $this->resetPage();
    }


    public function render()
    {
        $filters = [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'methodFilter' => $this->methodFilter,
            'partnerFilter' => $this->partnerFilter,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ];

        $params = [
            'payments' => $this->partnerPaymentService->getPayments($filters, self::PAGE_SIZE),
            'stats' => $this->getStats(),
            'paymentMethods' => PartnerPayment::distinct()->pluck('method'),
        ];

        return view('livewire.partner-payment-index', $params)
            ->extends('layouts.master')
            ->section('content');
    }

    public function getStats()
    {
        return [
            'total_payments' => PartnerPayment::count(),
            'pending_payments' => PartnerPayment::whereNull('validated_at')->whereNull('rejected_at')->count(),
            'validated_payments' => PartnerPayment::whereNotNull('validated_at')->count(),
            'rejected_payments' => PartnerPayment::whereNotNull('rejected_at')->count(),
            'total_amount' => PartnerPayment::whereNotNull('validated_at')->sum('amount'),
            'pending_amount' => PartnerPayment::whereNull('validated_at')->whereNull('rejected_at')->sum('amount'),
        ];
    }
}

