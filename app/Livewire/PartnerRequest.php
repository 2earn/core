<?php

namespace App\Livewire;

use App\Services\PartnerRequest\PartnerRequestService;
use Core\Enum\BePartnerRequestStatus;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerRequest extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = '';

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $partnerRequestService = app(PartnerRequestService::class);

        $partnerRequests = $partnerRequestService->getFilteredPartnerRequests(
            $this->searchTerm,
            $this->statusFilter,
            15
        );


        return view('livewire.partner-request', [
            'partnerRequests' => $partnerRequests,
            'statuses' => BePartnerRequestStatus::cases(),
        ])->extends('layouts.master')->section('content');
    }
}

