<?php

namespace App\Livewire;

use App\Enums\PlatformType;
use App\Models\EntityRole;
use App\Services\Platform\PlatformTypeChangeRequestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformTypeChangeRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';
    public $perPage = 10;

    public $showRejectModal = false;
    public $rejectRequestId = null;
    public $rejectionReason = '';

    public $showApproveModal = false;
    public $approveRequestId = null;

    protected $queryString = ['search', 'statusFilter'];

    protected PlatformTypeChangeRequestService $platformTypeChangeRequestService;

    public function boot(PlatformTypeChangeRequestService $platformTypeChangeRequestService)
    {
        $this->platformTypeChangeRequestService = $platformTypeChangeRequestService;
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

            $request = $this->platformTypeChangeRequestService->approveRequest(
                $this->approveRequestId,
                Auth::id()
            );

            DB::commit();

            Log::info('[PlatformTypeChangeRequests] Request approved', [
                'request_id' => $this->approveRequestId,
                'platform_id' => $request->platform_id,
                'old_type' => $request->old_type,
                'new_type' => $request->new_type,
            ]);

            session()->flash('success', Lang::get('Platform type change request approved successfully'));
            $this->closeApproveModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PlatformTypeChangeRequests] Error approving request', [
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
            DB::beginTransaction();

            $request = $this->platformTypeChangeRequestService->rejectRequest(
                $this->rejectRequestId,
                Auth::id(),
                $this->rejectionReason
            );

            if ($request->requestedBy) {
                $request->requestedBy->notify(new \App\Notifications\PlatformTypeChangeRequestRejected(
                    $request->platform,
                    $request->old_type,
                    $request->new_type,
                    $this->rejectionReason
                ));
            }

            DB::commit();

            Log::info('[PlatformTypeChangeRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'platform_id' => $request->platform_id,
                'rejection_reason' => $this->rejectionReason,
            ]);

            session()->flash('success', Lang::get('Platform type change request rejected successfully'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PlatformTypeChangeRequests] Error rejecting request', [
                'request_id' => $this->rejectRequestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error rejecting request: ') . $e->getMessage());
            $this->closeRejectModal();
        }
    }

    public function getTypeName($typeId)
    {
        return PlatformType::tryFrom($typeId)?->name ?? 'Unknown';
    }

    public function render()
    {
        $requests = $this->platformTypeChangeRequestService->getPaginatedRequests(
            $this->statusFilter,
            $this->search,
            $this->perPage
        );

        // Load EntityRoles for each platform
        foreach ($requests as $request) {
            if ($request->platform) {
                $request->platform->ownerRole = EntityRole::where('roleable_type', 'App\Models\Platform')
                    ->where('roleable_id', $request->platform->id)
                    ->where('name', 'owner')
                    ->with('user')
                    ->first();
            }
        }

        return view('livewire.platform-type-change-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

