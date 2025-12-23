<?php

namespace App\Livewire;

use App\Models\PartnerRequest;
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
        $query = PartnerRequest::with(['user', 'businessSector'])->orderBy('created_at', 'DESC');

        // Filter by search term
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }

        // Filter by status
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $partnerRequests = $query->paginate(15);

        return view('livewire.partner-request', [
            'partnerRequests' => $partnerRequests,
            'statuses' => BePartnerRequestStatus::cases(),
        ])->extends('layouts.master')->section('content');
    }
}

