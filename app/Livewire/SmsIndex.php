<?php

namespace App\Livewire;

use App\Models\Sms;
use App\Models\User;
use App\Services\sms\SmsService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class SmsIndex extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $date_from = '';
    public $date_to = '';
    public $destination_number = '';
    public $message = '';
    public $user_id = '';

    public $selectedSms = null;
    public $showDetailModal = false;

    // Statistics
    public $totalSms = 0;
    public $todaySms = 0;
    public $weekSms = 0;
    public $monthSms = 0;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'destination_number' => ['except' => ''],
        'message' => ['except' => ''],
        'user_id' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['date_from', 'date_to', 'destination_number', 'message', 'user_id']);
        $this->resetPage();
    }

    public function loadStatistics()
    {
        $smsService = app(SmsService::class);
        $stats = $smsService->getStatistics();

        $this->todaySms = $stats['today'];
        $this->weekSms = $stats['week'];
        $this->monthSms = $stats['month'];
        $this->totalSms = $stats['total'];
    }

    public function viewSms($id)
    {
        try {
            $sms = Sms::findOrFail($id);
            $user = null;

            if ($sms->created_by) {
                $user = User::with('mettaUser')->find($sms->created_by);
                if ($user && $user->mettaUser) {
                    $user = $user->mettaUser;
                }
            }

            $this->selectedSms = [
                'sms' => $sms,
                'user' => $user
            ];
            $this->showDetailModal = true;
        } catch (\Exception $e) {
            Log::error('Error viewing SMS: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedSms = null;
    }

    public function getSmsData()
    {
        $smsService = app(SmsService::class);

        $filters = [
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'destination_number' => $this->destination_number,
            'message' => $this->message,
            'user_id' => $this->user_id,
        ];

        return $smsService->getSmsData($filters, $this->perPage);
    }

    public function render()
    {
        $smsList = $this->getSmsData();

        return view('livewire.sms-index', [
            'smsList' => $smsList,
        ])->extends('layouts.master')->section('content');
    }
}

