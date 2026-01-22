<?php

namespace App\Livewire;

use App\Services\Partner\PartnerService;
use Livewire\Component;

class PartnerShow extends Component
{
    public $partner;
    public $partnerId;

    protected PartnerService $partnerService;

    public function boot(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function mount($id)
    {
        $this->partnerId = $id;
        $this->partner = $this->partnerService->getPartnerById($id);

        if (!$this->partner) {
            session()->flash('error', __('Partner not found'));
            return redirect()->route('partner_index', ['locale' => app()->getLocale()]);
        }
    }

    public function render()
    {
        return view('livewire.partner-show')->extends('layouts.master')->section('content');
    }
}

