<?php

namespace App\Livewire;

use App\Services\Deals\PendingDealValidationRequestsInlineService;
use Livewire\Component;

class PendingDealValidationRequestsInline extends Component
{
    public $limit = 5;

    protected PendingDealValidationRequestsInlineService $service;

    public function boot(PendingDealValidationRequestsInlineService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        $data = $this->service->getPendingRequestsWithTotal($this->limit);

        return view('livewire.pending-deal-validation-requests-inline', $data);
    }
}

