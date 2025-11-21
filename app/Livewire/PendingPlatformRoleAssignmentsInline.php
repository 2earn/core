<?php

namespace App\Livewire;

use App\Services\Platform\PendingPlatformRoleAssignmentsInlineService;
use Livewire\Component;

class PendingPlatformRoleAssignmentsInline extends Component
{
    public $limit = 5;

    protected PendingPlatformRoleAssignmentsInlineService $service;

    public function boot(PendingPlatformRoleAssignmentsInlineService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        $pendingAssignments = $this->service->getPendingAssignments($this->limit);

        return view('livewire.pending-platform-role-assignments-inline', [
            'pendingAssignments' => $pendingAssignments
        ]);
    }
}
