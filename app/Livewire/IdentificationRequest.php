<?php

namespace App\Livewire;

use App\Services\IdentificationRequestService;
use Livewire\Component;

class IdentificationRequest extends Component
{
    protected IdentificationRequestService $identificationRequestService;

    public function boot(IdentificationRequestService $identificationRequestService)
    {
        $this->identificationRequestService = $identificationRequestService;
    }

    public function render()
    {
        $identificationRequests = $this->identificationRequestService->getInProgressRequests();

        return view('livewire.identification-request', ['identificationRequests' => $identificationRequests])
            ->extends('layouts.master')
            ->section('content');
    }
}
