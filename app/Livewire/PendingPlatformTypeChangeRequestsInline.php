<?php

namespace App\Livewire;

use App\Models\PlatformTypeChangeRequest;
use Livewire\Component;

class PendingPlatformTypeChangeRequestsInline extends Component
{
    public $limit = 5;

    public function render()
    {
        $pendingRequests = PlatformTypeChangeRequest::with(['platform'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();

        $totalPending = PlatformTypeChangeRequest::where('status', 'pending')->count();

        return view('livewire.pending-platform-type-change-requests-inline', [
            'pendingRequests' => $pendingRequests,
            'totalPending' => $totalPending
        ]);
    }
}

