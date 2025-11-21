<?php

namespace App\Livewire;

use App\Models\AssignPlatformRole;
use Core\Models\Platform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AssignPlatformRolesIndex extends Component
{
    use WithPagination;

    public $selectedStatus = 'all';
    public $search = '';
    public $rejectionReason = '';
    public $selectedAssignmentId = null;
    public $showRejectModal = false;
    public $showApproveModal = false;

    protected $queryString = ['selectedStatus', 'search'];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function openApproveModal($assignmentId)
    {
        $this->selectedAssignmentId = $assignmentId;
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->selectedAssignmentId = null;
        $this->showApproveModal = false;
    }

    public function confirmApprove()
    {
        if ($this->selectedAssignmentId) {
            $this->approve($this->selectedAssignmentId);
            $this->closeApproveModal();
        }
    }

    public function approve($assignmentId)
    {
        try {
            DB::beginTransaction();

            $assignment = AssignPlatformRole::findOrFail($assignmentId);

            // Check if already processed
            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                session()->flash('error', 'This assignment has already been processed.');
                return;
            }

            $platform = Platform::findOrFail($assignment->platform_id);

            // Update the platform based on the role
            switch ($assignment->role) {
                case 'owner':
                    $platform->owner_id = $assignment->user_id;
                    break;
                case 'marketing_manager':
                    $platform->marketing_manager_id = $assignment->user_id;
                    break;
                case 'financial_manager':
                    $platform->financial_manager_id = $assignment->user_id;
                    break;
                default:
                    throw new \Exception('Invalid role: ' . $assignment->role);
            }

            $platform->updated_by = auth()->id();
            $platform->save();

            // Update assignment status
            $assignment->status = AssignPlatformRole::STATUS_APPROVED;
            $assignment->updated_by = auth()->id();
            $assignment->save();

            DB::commit();

            Log::info('[AssignPlatformRolesIndex] Role assignment approved', [
                'assignment_id' => $assignmentId,
                'user_id' => $assignment->user_id,
                'platform_id' => $assignment->platform_id,
                'role' => $assignment->role,
                'approved_by' => auth()->id()
            ]);

            session()->flash('success', 'Role assignment approved successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('[AssignPlatformRolesIndex] Failed to approve role assignment', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to approve assignment: ' . $e->getMessage());
        }
    }

    public function openRejectModal($assignmentId)
    {
        $this->selectedAssignmentId = $assignmentId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->selectedAssignmentId = null;
        $this->rejectionReason = '';
        $this->showRejectModal = false;
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500'
        ], [
            'rejectionReason.required' => 'Rejection reason is required',
            'rejectionReason.min' => 'Rejection reason must be at least 10 characters',
            'rejectionReason.max' => 'Rejection reason must not exceed 500 characters'
        ]);

        try {
            DB::beginTransaction();

            $assignment = AssignPlatformRole::findOrFail($this->selectedAssignmentId);

            // Check if already processed
            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                session()->flash('error', 'This assignment has already been processed.');
                $this->closeRejectModal();
                return;
            }

            // Update assignment status
            $assignment->status = AssignPlatformRole::STATUS_REJECTED;
            $assignment->rejection_reason = $this->rejectionReason;
            $assignment->updated_by = auth()->id();
            $assignment->save();

            DB::commit();

            Log::info('[AssignPlatformRolesIndex] Role assignment rejected', [
                'assignment_id' => $this->selectedAssignmentId,
                'user_id' => $assignment->user_id,
                'platform_id' => $assignment->platform_id,
                'role' => $assignment->role,
                'rejection_reason' => $this->rejectionReason,
                'rejected_by' => auth()->id()
            ]);

            session()->flash('success', 'Role assignment rejected successfully.');
            $this->closeRejectModal();

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('[AssignPlatformRolesIndex] Failed to reject role assignment', [
                'assignment_id' => $this->selectedAssignmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to reject assignment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = AssignPlatformRole::with(['platform', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($this->selectedStatus !== 'all') {
            $query->where('status', $this->selectedStatus);
        }

        // Search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('platform', function($platformQuery) {
                    $platformQuery->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('role', 'like', '%' . $this->search . '%');
            });
        }
        $assignments = $query->paginate(10);

        return view('livewire.assign-platform-roles-index', [
            'assignments' => $assignments
        ])->extends('layouts.master')->section('content');
    }
}

