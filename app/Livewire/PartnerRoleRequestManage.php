<?php

namespace App\Livewire;

use App\Models\PartnerRoleRequest;
use App\Models\Partner;
use App\Services\EntityRole\EntityRoleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerRoleRequestManage extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $searchUser = '';
    public $selectedRequest = null;
    public $showModal = false;
    public $modalType = ''; // 'approve' or 'reject'
    public $rejectionReason = '';

    protected EntityRoleService $entityRoleService;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function boot(EntityRoleService $entityRoleService)
    {
        $this->entityRoleService = $entityRoleService;
    }

    public function mount()
    {
        // Check if user has permission to manage partner role requests
        // You can add your authorization logic here
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSearchUser()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PartnerRoleRequest::with(['partner', 'user', 'requestedBy', 'reviewedBy', 'cancelledBy'])
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->searchUser)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }

        $requests = $query->paginate(15);

        $statistics = [
            'total' => PartnerRoleRequest::count(),
            'pending' => PartnerRoleRequest::pending()->count(),
            'approved' => PartnerRoleRequest::approved()->count(),
            'rejected' => PartnerRoleRequest::rejected()->count(),
        ];

        return view('livewire.partner-role-request-manage', [
            'requests' => $requests,
            'statistics' => $statistics,
        ]);
    }

    public function openApproveModal($requestId)
    {
        $this->selectedRequest = PartnerRoleRequest::with(['partner', 'user', 'requestedBy'])->find($requestId);

        if (!$this->selectedRequest || !$this->selectedRequest->canBeReviewed()) {
            session()->flash('error', Lang::get('This request cannot be approved'));
            return;
        }

        $this->modalType = 'approve';
        $this->showModal = true;
    }

    public function openRejectModal($requestId)
    {
        $this->selectedRequest = PartnerRoleRequest::with(['partner', 'user', 'requestedBy'])->find($requestId);

        if (!$this->selectedRequest || !$this->selectedRequest->canBeReviewed()) {
            session()->flash('error', Lang::get('This request cannot be rejected'));
            return;
        }

        $this->modalType = 'reject';
        $this->rejectionReason = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedRequest = null;
        $this->rejectionReason = '';
        $this->modalType = '';
    }

    public function approve()
    {
        if (!$this->selectedRequest || !$this->selectedRequest->canBeReviewed()) {
            session()->flash('error', Lang::get('This request cannot be approved'));
            $this->closeModal();
            return;
        }

        try {
            // Create the EntityRole
            $entityRole = $this->entityRoleService->createPartnerRole($this->selectedRequest->partner_id, [
                'name' => $this->selectedRequest->role_name,
                'user_id' => $this->selectedRequest->user_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Update the request
            $this->selectedRequest->update([
                'status' => PartnerRoleRequest::STATUS_APPROVED,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            session()->flash('success', Lang::get('Partner role request approved successfully'));

            Log::info('Partner role request approved', [
                'request_id' => $this->selectedRequest->id,
                'entity_role_id' => $entityRole->id,
                'approved_by' => Auth::id()
            ]);

            $this->closeModal();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            Log::error('Error approving partner role request: ' . $e->getMessage());
            session()->flash('error', Lang::get('Failed to approve request: ') . $e->getMessage());
            $this->closeModal();
        }
    }

    public function reject()
    {
        if (!$this->selectedRequest || !$this->selectedRequest->canBeReviewed()) {
            session()->flash('error', Lang::get('This request cannot be rejected'));
            $this->closeModal();
            return;
        }

        $this->validate([
            'rejectionReason' => 'required|string|min:5',
        ], [
            'rejectionReason.required' => Lang::get('Rejection reason is required'),
            'rejectionReason.min' => Lang::get('Rejection reason must be at least 5 characters'),
        ]);

        try {
            $this->selectedRequest->update([
                'status' => PartnerRoleRequest::STATUS_REJECTED,
                'rejection_reason' => $this->rejectionReason,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            session()->flash('success', Lang::get('Partner role request rejected successfully'));

            Log::info('Partner role request rejected', [
                'request_id' => $this->selectedRequest->id,
                'rejected_by' => Auth::id(),
                'reason' => $this->rejectionReason
            ]);

            $this->closeModal();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            Log::error('Error rejecting partner role request: ' . $e->getMessage());
            session()->flash('error', Lang::get('Failed to reject request: ') . $e->getMessage());
            $this->closeModal();
        }
    }

    public function cancelRequest($requestId)
    {
        $request = PartnerRoleRequest::find($requestId);

        if (!$request || !$request->canBeCancelled()) {
            session()->flash('error', Lang::get('This request cannot be cancelled'));
            return;
        }

        try {
            $request->update([
                'status' => PartnerRoleRequest::STATUS_CANCELLED,
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
                'cancelled_reason' => 'Cancelled by administrator',
            ]);

            session()->flash('success', Lang::get('Partner role request cancelled successfully'));

            Log::info('Partner role request cancelled', [
                'request_id' => $requestId,
                'cancelled_by' => Auth::id()
            ]);

            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            Log::error('Error cancelling partner role request: ' . $e->getMessage());
            session()->flash('error', Lang::get('Failed to cancel request: ') . $e->getMessage());
        }
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            PartnerRoleRequest::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            PartnerRoleRequest::STATUS_APPROVED => 'bg-green-100 text-green-800',
            PartnerRoleRequest::STATUS_REJECTED => 'bg-red-100 text-red-800',
            PartnerRoleRequest::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
