<?php

namespace App\Livewire;

use App\Services\Platform\AssignPlatformRoleService;
use Livewire\Component;
use Livewire\WithPagination;

class AssignPlatformRolesIndex extends Component
{
    use WithPagination;

    protected AssignPlatformRoleService $assignPlatformRoleService;

    public $selectedStatus = 'all';
    public $search = '';
    public $rejectionReason = '';
    public $selectedAssignmentId = null;
    public $showRejectModal = false;
    public $showApproveModal = false;

    protected $queryString = ['selectedStatus', 'search'];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function boot(AssignPlatformRoleService $assignPlatformRoleService)
    {
        $this->assignPlatformRoleService = $assignPlatformRoleService;
    }

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
        $result = $this->assignPlatformRoleService->approve($assignmentId, auth()->id());

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
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

        $result = $this->assignPlatformRoleService->reject(
            $this->selectedAssignmentId,
            $this->rejectionReason,
            auth()->id()
        );

        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->closeRejectModal();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function render()
    {
        $assignments = $this->assignPlatformRoleService->getPaginatedAssignments([
            'status' => $this->selectedStatus,
            'search' => $this->search,
        ], 10);

        return view('livewire.assign-platform-roles-index', [
            'assignments' => $assignments
        ])->extends('layouts.master')->section('content');
    }
}

