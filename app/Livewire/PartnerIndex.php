<?php

namespace App\Livewire;

use App\Services\Partner\PartnerService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 10;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    protected PartnerService $partnerService;

    public function boot(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePartner($idPartner)
    {
        $this->partnerService->deletePartner($idPartner);
        return redirect()->route('partner_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Partner Deleted Successfully'));
    }

    public function render()
    {
        $params['partners'] = $this->partnerService->getFilteredPartners($this->search, self::PAGE_SIZE);
        return view('livewire.partner-index', $params)->extends('layouts.master')->section('content');
    }
}

