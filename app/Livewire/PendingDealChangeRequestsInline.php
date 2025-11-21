<?php

namespace App\Livewire;

use App\Services\Deals\PendingDealChangeRequestsInlineService;
use Livewire\Component;

class PendingDealChangeRequestsInline extends Component
{
    public $limit = 5;

    protected PendingDealChangeRequestsInlineService $service;

    public function boot(PendingDealChangeRequestsInlineService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        $data = $this->service->getPendingRequestsWithTotal($this->limit);

        return view('livewire.pending-deal-change-requests-inline', $data);
    }
}

