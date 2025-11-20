<?php

namespace App\Livewire;

use App\Models\PlatformValidationRequest;
use Livewire\Component;

class PendingPlatformValidationRequestsInline extends Component
{
    public $limit = 5;

    public function render()
    {
        $pendingRequests = PlatformValidationRequest::with(['platform'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();

        $totalPending = PlatformValidationRequest::where('status', 'pending')->count();

        return view('livewire.pending-platform-validation-requests-inline', [
            'pendingRequests' => $pendingRequests,
            'totalPending' => $totalPending
        ]);
    }
}

