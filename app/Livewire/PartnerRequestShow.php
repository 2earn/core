<?php

namespace App\Livewire;

use App\Models\PartnerRequest as PartnerRequestModel;
use App\Services\PartnerRequest\PartnerRequestService;
use Core\Enum\BePartnerRequestStatus;
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
        $partnerRequestService->updatePartnerRequest($this->partnerId, [
            'status' => BePartnerRequestStatus::Validated->value,
            'examination_date' => now(),
            'examiner_id' => auth()->user()->id,
        ]);

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

