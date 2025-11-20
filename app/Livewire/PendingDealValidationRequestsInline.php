<?php

namespace App\Livewire;

use App\Models\DealValidationRequest;
use Livewire\Component;

class PendingDealValidationRequestsInline extends Component
{
    public $limit = 5;

    public function render()
    {
        $pendingRequests = DealValidationRequest::with(['deal.platform', 'requestedBy'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();

        $totalPending = DealValidationRequest::where('status', 'pending')->count();

        return view('livewire.pending-deal-validation-requests-inline', [
            'pendingRequests' => $pendingRequests,
            'totalPending' => $totalPending
        ]);
    }
}

