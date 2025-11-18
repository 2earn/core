<?php

namespace App\Livewire;

use App\Models\PlatformValidationRequest;
use Core\Models\Platform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformValidationRequests extends Component
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

            $request = PlatformValidationRequest::findOrFail($this->approveRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeApproveModal();
                return;
            }

            // Enable the platform
            $platform = Platform::findOrFail($request->platform_id);
            $platform->enabled = true;
            $platform->updated_by = Auth::id();
            $platform->save();

            // Update request status
            $request->status = 'approved';
            $request->save();

            DB::commit();

            Log::info('[PlatformValidationRequests] Request approved', [
                'request_id' => $this->approveRequestId,
                'platform_id' => $request->platform_id,
                'approved_by' => Auth::id(),
            ]);

            session()->flash('success', Lang::get('Platform validation request approved successfully'));
            $this->closeApproveModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PlatformValidationRequests] Error approving request', [
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
            $request = PlatformValidationRequest::findOrFail($this->rejectRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeRejectModal();
                return;
            }

            $request->status = 'rejected';
            $request->rejection_reason = $this->rejectionReason;
            $request->save();

            Log::info('[PlatformValidationRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'platform_id' => $request->platform_id,
                'rejection_reason' => $this->rejectionReason,
            ]);

            session()->flash('success', Lang::get('Platform validation request rejected successfully'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
            Log::error('[PlatformValidationRequests] Error rejecting request', [
                'request_id' => $this->rejectRequestId,
                'error' => $e->getMessage()
            ]);
            session()->flash('danger', Lang::get('Error rejecting request: ') . $e->getMessage());
            $this->closeRejectModal();
        }
    }

    public function render()
    {
        $requests = PlatformValidationRequest::with('platform')
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

        return view('livewire.platform-validation-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

