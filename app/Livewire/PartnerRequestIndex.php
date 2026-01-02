<?php

namespace App\Livewire;

use App\Enums\BePartnerRequestStatus;
use App\Services\PartnerRequest\PartnerRequestService;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerRequestIndex extends Component
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


        return view('livewire.partner-request-index', [
            'partnerRequests' => $partnerRequests,
            'statuses' => BePartnerRequestStatus::cases(),
        ])->extends('layouts.master')->section('content');
    }
}

