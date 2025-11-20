<?php

namespace App\Livewire;

use App\Models\DealChangeRequest;
use Livewire\Component;

class PendingDealChangeRequestsInline extends Component
{
    public $limit = 5;

    public function render()
    {
        $pendingRequests = DealChangeRequest::with(['deal.platform', 'requestedBy'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();

        $totalPending = DealChangeRequest::where('status', 'pending')->count();

        return view('livewire.pending-deal-change-requests-inline', [
            'pendingRequests' => $pendingRequests,
            'totalPending' => $totalPending
        ]);
    }
}

