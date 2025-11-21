<?php

namespace App\Livewire;

use App\Services\Platform\PendingPlatformChangeRequestsInlineService;
use Livewire\Component;

class PendingPlatformChangeRequestsInline extends Component
{
    public $limit = 5;

    protected PendingPlatformChangeRequestsInlineService $service;

    public function boot(PendingPlatformChangeRequestsInlineService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        $data = $this->service->getPendingRequestsWithTotal($this->limit);

        return view('livewire.pending-platform-change-requests-inline', $data);
    }
}

