<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\DealValidationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
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

    protected $queryString = ['search', 'statusFilter'];

    protected $listeners = [
        'refreshValidationRequests' => '$refresh'
    ];

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

            $request = DealValidationRequest::findOrFail($this->approveRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeApproveModal();
                return;
            }

            // Validate the deal
            $deal = Deal::findOrFail($request->deal_id);
            $deal->validated = true;
            $deal->updated_by = Auth::id();
            $deal->save();

            // Update request status
            $request->status = 'approved';
            $request->save();

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

            $request = DealValidationRequest::findOrFail($this->rejectRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeRejectModal();
                return;
            }

            // Update request status
            $request->status = 'rejected';
            $request->rejection_reason = $this->rejectionReason;
            $request->save();

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
        $query = DealValidationRequest::with(['deal.platform', 'requestedBy'])
            ->orderBy('created_at', 'desc');

        // Apply status filter
        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply search filter
        if ($this->search) {
            $query->whereHas('deal', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhereHas('requestedBy', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Only show requests for platforms the user manages (if not super admin)
        if (!User::isSuperAdmin()) {
            $query->whereHas('deal.platform', function ($q) {
                $q->where('financial_manager_id', Auth::id())
                    ->orWhere('marketing_manager_id', Auth::id())
                    ->orWhere('owner_id', Auth::id());
            });
        }

        $requests = $query->paginate($this->perPage);

        return view('livewire.deal-validation-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

