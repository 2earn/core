<?php

namespace App\Livewire;

use App\Models\DealChangeRequest;
use App\Services\Deals\DealService;
use App\Services\Deals\PendingDealChangeRequestsInlineService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class DealChangeRequests extends Component
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
    public $approveRequestChanges = [];

    // View changes modal
    public $showChangesModal = false;
    public $viewChangesRequestId = null;
    public $viewChangesData = [];

    protected $queryString = ['search', 'statusFilter'];

    protected PendingDealChangeRequestsInlineService $dealChangeRequestService;

    public function boot(PendingDealChangeRequestsInlineService $dealChangeRequestService)
    {
        $this->dealChangeRequestService = $dealChangeRequestService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openChangesModal($requestId)
    {
        $request = $this->dealChangeRequestService->findRequestWithRelations($requestId, ['deal']);
        $this->viewChangesRequestId = $requestId;
        $this->viewChangesData = [
            'deal_name' => $request->deal->name ?? 'N/A',
            'platform_name' => $request->deal->platform->name ?? 'N/A',
            'changes' => $request->changes,
            'status' => $request->status,
            'requested_at' => $request->created_at->format(config('app.date_format')),
        ];
        $this->showChangesModal = true;
    }

    public function closeChangesModal()
    {
        $this->showChangesModal = false;
        $this->viewChangesRequestId = null;
        $this->viewChangesData = [];
    }

    public function openApproveModal($requestId)
    {
        $request = $this->dealChangeRequestService->findRequest($requestId);
        $this->approveRequestId = $requestId;
        $this->approveRequestChanges = $request->changes;
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->approveRequestId = null;
        $this->approveRequestChanges = [];
    }

    public function approveRequest()
    {
        try {
            DB::beginTransaction();

            $request = $this->dealChangeRequestService->findRequest($this->approveRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeApproveModal();
                return;
            }

            $deal = app(DealService::class)->find($request->deal_id);

            if (!$deal) {
                session()->flash('danger', Lang::get('Deal not found'));
                $this->closeApproveModal();
                return;
            }

            foreach ($request->changes as $field => $change) {
                $deal->{$field} = $change['new'];
            }

            $deal->save();

            $request->status = 'approved';
            $request->reviewed_by = Auth::id();
            $request->reviewed_at = now();
            $request->save();

            DB::commit();

            Log::info('[DealChangeRequests] Request approved', [
                'request_id' => $this->approveRequestId,
                'deal_id' => $request->deal_id,
                'changes' => $request->changes,
                'reviewed_by' => auth()->id(),
            ]);

            session()->flash('success', Lang::get('Deal change request approved successfully'));
            $this->closeApproveModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[DealChangeRequests] Error approving request', [
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
            'rejectionReason' => 'required|string|min:10|max:1000',
        ], [
            'rejectionReason.required' => Lang::get('Rejection reason is required'),
            'rejectionReason.min' => Lang::get('Rejection reason must be at least 10 characters'),
            'rejectionReason.max' => Lang::get('Rejection reason must not exceed 1000 characters'),
        ]);

        try {
            $request = $this->dealChangeRequestService->findRequest($this->rejectRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeRejectModal();
                return;
            }

            $request->status = 'rejected';
            $request->rejection_reason = $this->rejectionReason;
            $request->reviewed_by = Auth::id();
            $request->reviewed_at = now();
            $request->save();

            Log::info('[DealChangeRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'deal_id' => $request->deal_id,
                'rejection_reason' => $this->rejectionReason,
                'reviewed_by' => auth()->id(),
            ]);

            session()->flash('success', Lang::get('Deal change request rejected successfully'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
            Log::error('[DealChangeRequests] Error rejecting request', [
                'request_id' => $this->rejectRequestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error rejecting request: ') . $e->getMessage());
            $this->closeRejectModal();
        }
    }

    public function getFieldLabel($field)
    {
        $labels = [
            'name' => 'Deal Name',
            'description' => 'Description',
            'type' => 'Type',
            'status' => 'Status',
            'current_turnover' => 'Current Turnover',
            'target_turnover' => 'Target Turnover',
            'is_turnover' => 'Is Turnover',
            'discount' => 'Discount',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'initial_commission' => 'Initial Commission',
            'final_commission' => 'Final Commission',
            'earn_profit' => 'Earn Profit',
            'jackpot' => 'Jackpot',
            'tree_remuneration' => 'Tree Remuneration',
            'proactive_cashback' => 'Proactive Cashback',
            'cash_company_profit' => 'Cash Company Profit',
            'cash_jackpot' => 'Cash Jackpot',
            'cash_tree' => 'Cash Tree',
            'cash_cashback' => 'Cash Cashback',
        ];

        return __($labels[$field] ?? ucfirst(str_replace('_', ' ', $field)));
    }

    public function render()
    {
        $requests = DealChangeRequest::with(['deal.platform', 'requestedBy', 'reviewedBy'])
            ->when($this->search, function ($query) {
                $query->whereHas('deal', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.deal-change-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

