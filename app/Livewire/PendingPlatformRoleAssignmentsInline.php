<?php

namespace App\Livewire;

use App\Models\AssignPlatformRole;
use Livewire\Component;

class PendingPlatformRoleAssignmentsInline extends Component
{
    public function render()
    {
        $pendingAssignments = AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)
            ->with(['platform', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.pending-platform-role-assignments-inline', [
            'pendingAssignments' => $pendingAssignments
        ]);
    }
}
