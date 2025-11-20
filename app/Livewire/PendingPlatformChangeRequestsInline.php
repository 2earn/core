<?php

namespace App\Livewire;

use App\Models\PlatformChangeRequest;
use Livewire\Component;

class PendingPlatformChangeRequestsInline extends Component
{
    public $limit = 5;

    public function render()
    {
        $pendingRequests = PlatformChangeRequest::with(['platform', 'requestedBy'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();

        $totalPending = PlatformChangeRequest::where('status', 'pending')->count();

        return view('livewire.pending-platform-change-requests-inline', [
            'pendingRequests' => $pendingRequests,
            'totalPending' => $totalPending
        ]);
    }
}

