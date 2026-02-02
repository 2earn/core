<?php

namespace App\Livewire;

use App\Services\NotificationService;
use App\Services\settingsManager;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationHistory extends Component
{
    use WithPagination;

    protected NotificationService $notificationService;
    protected $paginationTheme = 'bootstrap';

    public ?string $search = "";
    public ?string $pageCount = "10";
    public ?string $filterDetails = "";
    public ?string $filterReference = "";
    public ?string $filterSource = "";
    public ?string $filterReceiver = "";
    public ?string $filterActions = "";
    public ?string $filterDate = "";
    public ?string $filterType = "";
    public ?string $filterResponse = "";

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    protected function queryString()
    {
        return [
            'search' => ['as' => 'q'],
            'pageCount' => ['as' => 'pc'],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPageCount()
    {
        $this->resetPage();
    }

    public function updatingFilterDetails()
    {
        $this->resetPage();
    }

    public function updatingFilterReference()
    {
        $this->resetPage();
    }

    public function updatingFilterSource()
    {
        $this->resetPage();
    }

    public function updatingFilterReceiver()
    {
        $this->resetPage();
    }

    public function updatingFilterActions()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterResponse()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterDetails', 'filterReference', 'filterSource', 'filterReceiver', 'filterActions', 'filterDate', 'filterType', 'filterResponse']);
        $this->resetPage();
    }

    public function getNotificationsProperty()
    {
        // Build filters array from component properties
        $filters = [
            'search' => $this->search,
            'filterReference' => $this->filterReference,
            'filterSource' => $this->filterSource,
            'filterReceiver' => $this->filterReceiver,
            'filterActions' => $this->filterActions,
            'filterDate' => $this->filterDate,
            'filterType' => $this->filterType,
            'filterResponse' => $this->filterResponse,
        ];

        // Get paginated history using service
        return $this->notificationService->getPaginatedHistory(
            $filters,
            (int)$this->pageCount,
            $this->getPage('page')
        );
    }

    public function render()
    {
        return view('livewire.notification-history', [
            'notifications' => $this->notifications
        ])->extends('layouts.master')->section('content');
    }
}
