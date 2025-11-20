<?php

namespace App\Livewire;

use App\Models\PlatformChangeRequest;
use Core\Models\Platform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformChangeRequests extends Component
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
        $request = PlatformChangeRequest::with('platform')->findOrFail($requestId);
        $this->viewChangesRequestId = $requestId;
        $this->viewChangesData = [
            'platform_name' => $request->platform->name ?? 'N/A',
            'changes' => $request->changes,
            'status' => $request->status,
            'requested_at' => $request->created_at->format('Y-m-d H:i:s'),
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
        $request = PlatformChangeRequest::findOrFail($requestId);
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

            $request = PlatformChangeRequest::findOrFail($this->approveRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeApproveModal();
                return;
            }

            // Update platform with changes
            $platform = Platform::findOrFail($request->platform_id);

            foreach ($request->changes as $field => $change) {
                $platform->{$field} = $change['new'];
            }

            $platform->save();

            // Update request status
            $request->status = 'approved';
            $request->reviewed_by = auth()->id();
            $request->reviewed_at = now();
            $request->save();

            DB::commit();

            Log::info('[PlatformChangeRequests] Request approved', [
                'request_id' => $this->approveRequestId,
                'platform_id' => $request->platform_id,
                'changes' => $request->changes,
                'reviewed_by' => auth()->id(),
            ]);

            session()->flash('success', Lang::get('Platform change request approved successfully'));
            $this->closeApproveModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PlatformChangeRequests] Error approving request', [
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
            $request = PlatformChangeRequest::findOrFail($this->rejectRequestId);

            if ($request->status !== 'pending') {
                session()->flash('danger', Lang::get('This request has already been processed'));
                $this->closeRejectModal();
                return;
            }

            $request->status = 'rejected';
            $request->rejection_reason = $this->rejectionReason;
            $request->reviewed_by = auth()->id();
            $request->reviewed_at = now();
            $request->save();

            Log::info('[PlatformChangeRequests] Request rejected', [
                'request_id' => $this->rejectRequestId,
                'platform_id' => $request->platform_id,
                'rejection_reason' => $this->rejectionReason,
                'reviewed_by' => auth()->id(),
            ]);

            session()->flash('success', Lang::get('Platform change request rejected successfully'));
            $this->closeRejectModal();

        } catch (\Exception $e) {
            Log::error('[PlatformChangeRequests] Error rejecting request', [
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
            'name' => 'Platform Name',
            'description' => 'Description',
            'link' => 'Link',
            'enabled' => 'Enabled Status',
            'type' => 'Type',
            'show_profile' => 'Show Profile',
            'image_link' => 'Image Link',
            'owner_id' => 'Owner',
            'marketing_manager_id' => 'Marketing Manager',
            'financial_manager_id' => 'Financial Manager',
            'business_sector_id' => 'Business Sector',
        ];

        return __($labels[$field] ?? ucfirst(str_replace('_', ' ', $field)));
    }

    public function render()
    {
        $requests = PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy'])
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

        return view('livewire.platform-change-requests', [
            'requests' => $requests
        ])->extends('layouts.master')->section('content');
    }
}

