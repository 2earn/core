<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Services\Partner\PartnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PartnerCreateUpdate extends Component
{
    public $id;
    public $company_name;
    public $business_sector_id;
    public $platform_url;
    public $platform_description;
    public $partnership_reason;
    public $businessSectors = [];

    public $update = false;

    protected PartnerService $partnerService;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'business_sector_id' => 'nullable|exists:business_sectors,id',
        'platform_url' => 'nullable|url|max:500',
        'platform_description' => 'nullable|string',
        'partnership_reason' => 'nullable|string',
    ];

    protected $messages = [
        'company_name.required' => 'Company name is required',
        'business_sector_id.exists' => 'Invalid business sector selected',
        'platform_url.url' => 'Platform URL must be a valid URL',
    ];

    public function boot(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function mount(Request $request)
    {
        $this->businessSectors = BusinessSector::orderBy('name')->get();
        $this->id = $request->input('id');
        if (!is_null($this->id)) {
            $this->edit($this->id);
        }
    }

    public function edit($id)
    {
        $partner = $this->partnerService->getPartnerById($id);

        if (!$partner) {
            return redirect()->route('partner_index', ['locale' => app()->getLocale()])
                ->with('error', Lang::get('Partner not found'));
        }

        $this->id = $id;
        $this->company_name = $partner->company_name;
        $this->business_sector_id = $partner->business_sector_id;
        $this->platform_url = $partner->platform_url;
        $this->platform_description = $partner->platform_description;
        $this->partnership_reason = $partner->partnership_reason;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('partner_index', ['locale' => app()->getLocale()])
            ->with('warning', Lang::get('Partner operation canceled'));
    }

    public function updatePartner()
    {
        $this->validate();

        try {
            $this->partnerService->updatePartner($this->id, [
                'company_name' => $this->company_name,
                'business_sector_id' => $this->business_sector_id,
                'platform_url' => $this->platform_url,
                'platform_description' => $this->platform_description,
                'partnership_reason' => $this->partnership_reason,
                'updated_by' => Auth::id(),
            ]);
        } catch (\Exception $exception) {
            Log::error('Error updating partner: ' . $exception->getMessage());
            return redirect()->route('partner_index', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Something went wrong while updating Partner'));
        }

        return redirect()->route('partner_index', ['locale' => app()->getLocale()])
            ->with('success', Lang::get('Partner Updated Successfully'));
    }

    public function store()
    {
        $this->validate();

        $partnerData = [
            'company_name' => $this->company_name,
            'business_sector_id' => $this->business_sector_id,
            'platform_url' => $this->platform_url,
            'platform_description' => $this->platform_description,
            'partnership_reason' => $this->partnership_reason,
            'created_by' => Auth::id(),
        ];

        try {
            $this->partnerService->createPartner($partnerData);
        } catch (\Exception $exception) {
            Log::error('Error creating partner: ' . $exception->getMessage());
            return redirect()->route('partner_index', ['locale' => app()->getLocale()])
                ->with('danger', Lang::get('Something went wrong while creating Partner'));
        }

        return redirect()->route('partner_index', ['locale' => app()->getLocale()])
            ->with('success', Lang::get('Partner Created Successfully'));
    }

    public function render()
    {
        return view('livewire.partner-create-update')->extends('layouts.master')->section('content');
    }
}

