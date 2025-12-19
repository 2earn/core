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

    public function deletePartnerPayment($paymentId)
    {
        try {
            $this->partnerPaymentService->delete($paymentId);
            $this->dispatch('refreshList');
            session()->flash('success', Lang::get('Partner payment deleted successfully'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getPayments()
    {
        $query = PartnerPayment::with(['user', 'partner', 'validator']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('method', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('partner', function ($partnerQuery) {
                        $partnerQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter === 'pending') {
            $query->whereNull('validated_at');
        } elseif ($this->statusFilter === 'validated') {
            $query->whereNotNull('validated_at');
        }

        if (!empty($this->methodFilter)) {
            $query->where('method', $this->methodFilter);
        }

        if (!empty($this->partnerFilter)) {
            $query->where('partner_id', $this->partnerFilter);
        }

        if (!empty($this->fromDate)) {
            $query->where('payment_date', '>=', $this->fromDate);
        }

        if (!empty($this->toDate)) {
            $query->where('payment_date', '<=', $this->toDate);
        }

        return $query->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
    }

    public function getStats()
    {
        return [
            'total_payments' => PartnerPayment::count(),
            'pending_payments' => PartnerPayment::whereNull('validated_at')->count(),
            'validated_payments' => PartnerPayment::whereNotNull('validated_at')->count(),
            'total_amount' => PartnerPayment::whereNotNull('validated_at')->sum('amount'),
            'pending_amount' => PartnerPayment::whereNull('validated_at')->sum('amount'),
        ];
    }

    public function render()
    {
        $params = [
            'payments' => $this->getPayments(),
            'stats' => $this->getStats(),
            'paymentMethods' => PartnerPayment::distinct()->pluck('method'),
        ];

        return view('livewire.partner-payment-index', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}

