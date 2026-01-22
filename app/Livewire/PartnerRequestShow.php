<?php

namespace App\Livewire;

use App\Enums\BePartnerRequestStatus;
use App\Notifications\PartnershipRequestRejected;
use App\Notifications\PartnershipRequestValidated;
use App\Services\Partner\PartnerService;
use App\Services\PartnerRequest\PartnerRequestService;
use Livewire\Component;

class PartnerRequestShow extends Component
{
    public $partnerId;
    public $partnerRequest;
    public $rejectionNote = '';
    public $confirmReject = false;

    public function mount($id)
    {
        $this->partnerId = $id;
        $partnerRequestService = app(PartnerRequestService::class);
        $this->partnerRequest = $partnerRequestService->getPartnerRequestById($id);

        if (is_null($this->partnerRequest)) {
            abort(404);
        }
    }

    public function validatePartnerRequest()
    {
        $partnerRequestService = app(PartnerRequestService::class);

        // Update partner request status
        $partnerRequestService->updatePartnerRequest($this->partnerId, [
            'status' => BePartnerRequestStatus::Validated->value,
            'examination_date' => now(),
            'examiner_id' => auth()->user()->id,
        ]);

        $partnerService = app(PartnerService::class);

        $partnerService->createPartner([
            'company_name' => $this->partnerRequest->company_name,
            'business_sector_id' => $this->partnerRequest->business_sector_id,
            'platform_url' => $this->partnerRequest->platform_url,
            'platform_description' => $this->partnerRequest->platform_description,
            'partnership_reason' => $this->partnerRequest->partnership_reason,
            'created_by' => auth()->user()->id,
        ]);

        // Notify the user
        $this->partnerRequest->user->notify(new PartnershipRequestValidated());

        return redirect()->route('requests_partner', app()->getLocale())
            ->with('success', __('Partner request has been validated successfully'));
    }

    public function rejectPartnerRequest()
    {
        $this->validate([
            'rejectionNote' => 'required|string|min:5',
        ]);

        $partnerRequestService = app(PartnerRequestService::class);
        $partnerRequestService->updatePartnerRequest($this->partnerId, [
            'status' => BePartnerRequestStatus::Rejected->value,
            'note' => $this->rejectionNote,
            'examination_date' => now(),
            'examiner_id' => auth()->user()->id,
        ]);

        $this->partnerRequest->user->notify(new PartnershipRequestRejected($this->rejectionNote));

        return redirect()->route('requests_partner', app()->getLocale())
            ->with('success', __('Partner request has been rejected'));
    }

    public function render()
    {
        return view('livewire.partner-request-show')
            ->extends('layouts.master')
            ->section('content');
    }
}

