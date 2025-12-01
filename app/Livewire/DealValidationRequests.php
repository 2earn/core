<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\DealValidationRequest;
use App\Models\User;
use App\Services\Deals\PendingDealValidationRequestsInlineService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class DealValidationRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';
    public $perPage = 10;

    // Rejection modal properties
    public $showRejectModal = false;
    public $rejectRequestId = null;
    public $rejectionReason = '';

    // Approval modal properties
    public $showApproveModal = false;
    public $approveRequestId = null;

    public $currentRouteName = null;

    protected $queryString = ['search', 'statusFilter'];

    protected $listeners = [
        'refreshValidationRequests' => '$refresh'
    ];

    protected PendingDealValidationRequestsInlineService $dealValidationRequestService;

    public function boot(PendingDealValidationRequestsInlineService $dealValidationRequestService)
    {
        $this->dealValidationRequestService = $dealValidationRequestService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openApproveModal($requestId)
    {
        $this->approveRequestId = $requestId;
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->approveRequestId = null;
    }

    public function approveRequest()
    {
        try {
            DB::beginTransaction();

            $request = $this->dealValidationRequestService->approveRequest(
                $this->approveRequestId,
                Auth::id()
            );

            DB::commit();

            Log::info('[DealValidationRequests] Request approved', [
                'request_id' => $this->approveRequestId,
                'deal_id' => $request->deal_id,
                'approved_by' => Auth::id(),
            ]);

            session()->flash('success', Lang::get('Deal validation request approved successfully'));
            $this->closeApproveModal();
            $this->dispatch('refreshDeals');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[DealValidationRequests] Error approving request', [
                'request_id' => $this->approveRequestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error approving request: ') . $e->getMessage());
            $this->closeApproveModal();
        }
    }

    public function openRejectModal($requestId)
    {
        $this->rejectRequestId = $requestId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectRequestId = null;
        $this->rejectionReason = '';
    }

    public function rejectRequest()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10',
        ], [
            'rejectionReason.required' => Lang::get('Please provide a reason for rejection'),
            'rejectionReason.min' => Lang::get('Rejection reason must be at least 10 characters'),
        ]);

        try {
            DB::beginTransaction();

            $request = $this->dealValidationRequestService->rejectRequest(
                $this->rejectRequestId,
                Auth::id(),
                $this->rejectionReason
            );

            DB::commit();

            Log::info('[DealValidationRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'deal_id' => $request->deal_id,
                'rejected_by' => Auth::id(),
                'reason' => $this->rejectionReason
            ]);

            session()->flash('success', Lang::get('Deal validation request rejected'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[DealValidationRequests] Error rejecting request', [
                'request_id' => $this->rejectRequestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error rejecting request: ') . $e->getMessage());
            $this->closeRejectModal();
        }
    }

    public function render()
    {
        $requests = $this->dealValidationRequestService->getPaginatedRequests(
            $this->statusFilter,
            $this->search,
            Auth::id(),
            User::isSuperAdmin(),
            $this->perPage
        );

        return view('livewire.deal-validation-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

