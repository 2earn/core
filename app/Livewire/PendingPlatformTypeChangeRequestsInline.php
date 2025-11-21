<?php

namespace App\Livewire;

use App\Services\Platform\PlatformTypeChangeRequestService;
use Livewire\Component;

class PendingPlatformTypeChangeRequestsInline extends Component
{
    public $limit = 5;

    protected PlatformTypeChangeRequestService $service;

    public function boot(PlatformTypeChangeRequestService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        $data = $this->service->getPendingRequestsWithTotal($this->limit);

        return view('livewire.pending-platform-type-change-requests-inline', $data);
    }
}

