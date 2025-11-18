<?php

namespace App\Livewire;

use App\Models\PlatformTypeChangeRequest;
use Core\Enum\PlatformType;
use Core\Models\Platform;
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

    // Rejection modal properties
    public $showRejectModal = false;
    public $rejectRequestId = null;
    public $rejectionReason = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approveRequest($requestId)
    {
        try {
            DB::beginTransaction();

            $request = PlatformTypeChangeRequest::findOrFail($requestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                return;
            }

            // Update platform type
            $platform = Platform::findOrFail($request->platform_id);
            $platform->type = $request->new_type;
            $platform->save();

            // Update request status
            $request->status = 'approved';
            $request->save();

            DB::commit();

            Log::info('[PlatformTypeChangeRequests] Request approved', [
                'request_id' => $requestId,
                'platform_id' => $request->platform_id,
                'old_type' => $request->old_type,
                'new_type' => $request->new_type,
            ]);

            session()->flash('success', Lang::get('Platform type change request approved successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PlatformTypeChangeRequests] Error approving request', [
                'request_id' => $requestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error approving request: ') . $e->getMessage());
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
            $request = PlatformTypeChangeRequest::findOrFail($this->rejectRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeRejectModal();
                return;
            }

            $request->status = 'rejected';
            $request->rejection_reason = $this->rejectionReason;
            $request->save();

            Log::info('[PlatformTypeChangeRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'platform_id' => $request->platform_id,
                'rejection_reason' => $this->rejectionReason,
            ]);

            session()->flash('success', Lang::get('Platform type change request rejected successfully'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
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
        $requests = PlatformTypeChangeRequest::with('platform')
            ->when($this->search, function ($query) {
                $query->whereHas('platform', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.platform-type-change-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

