<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Services\PartnerRequest\PartnerRequestService;
use Core\Enum\BePartnerRequestStatus;
use Livewire\Component;

class PartnerRequestForm extends Component
{
    public $companyName = '';
    public $businessSectorId = '';
    public $platformUrl = '';
    public $platformDescription = '';
    public $partnershipReason = '';
    public $businessSectors = [];

    public function mount()
    {
        $this->businessSectors = BusinessSector::all();
    }

    public function rules()
    {
        return [
            'companyName' => 'required|string|max:255',
            'businessSectorId' => 'required|exists:business_sectors,id',
            'platformUrl' => 'required|url',
            'platformDescription' => 'required|string|min:10',
            'partnershipReason' => 'required|string|min:20',
        ];
    }

    public function messages()
    {
        return [
            'companyName.required' => __('Company name is required'),
            'companyName.string' => __('Company name must be a string'),
            'companyName.max' => __('Company name cannot exceed 255 characters'),
            'businessSectorId.required' => __('Business sector is required'),
            'businessSectorId.exists' => __('Selected business sector is invalid'),
            'platformUrl.required' => __('Platform URL is required'),
            'platformUrl.url' => __('Platform URL must be a valid URL'),
            'platformDescription.required' => __('Platform description is required'),
            'platformDescription.min' => __('Platform description must be at least 10 characters'),
            'partnershipReason.required' => __('Reason for partnership request is required'),
            'partnershipReason.min' => __('Reason for partnership request must be at least 20 characters'),
        ];
    }

    public function submitForm()
    {
        $this->validate();

        $partnerRequestService = app(PartnerRequestService::class);

        // Check if user already has an in-progress request
        if ($partnerRequestService->hasInProgressRequest(auth()->user()->id)) {
            return redirect()->route('business_hub_additional_income', app()->getLocale())
                ->with('warning', __('You already have a partner request under review'));
        }

        // Create the partner request
        $partnerRequest = $partnerRequestService->createPartnerRequest([
            'company_name' => $this->companyName,
            'business_sector_id' => $this->businessSectorId,
            'platform_url' => $this->platformUrl,
            'platform_description' => $this->platformDescription,
            'partnership_reason' => $this->partnershipReason,
            'user_id' => auth()->user()->id,
            'request_date' => now(),
            'status' => BePartnerRequestStatus::InProgress->value,
        ]);
        if ($partnerRequest) {
            return redirect()->route('business_hub_additional_income', app()->getLocale())
                ->with('success', __('Your partner request has been submitted successfully. Please wait for admin validation.'));
        } else {
            session()->flash('error', __('Failed to submit partner request. Please try again.'));
        }
    }

    public function render()
    {
        return view('livewire.partner-request-form')
            ->extends('layouts.master')
            ->section('content');
    }
}

