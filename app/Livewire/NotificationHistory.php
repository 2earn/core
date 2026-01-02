<?php

namespace App\Livewire;

use App\Services\settingsManager;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationHistory extends Component
{
    use WithPagination;

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

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'pageCount' => [
                'as' => 'pc',
            ],
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
        $notifications = app(settingsManager::class)->getHistory();

        // Apply filters
        if ($this->search) {
            $searchTerm = strtolower($this->search);
            $notifications = $notifications->filter(function ($item) use ($searchTerm) {
                return str_contains(strtolower($item->reference ?? ''), $searchTerm)
                    || str_contains(strtolower($item->send ?? ''), $searchTerm)
                    || str_contains(strtolower($item->receiver ?? ''), $searchTerm)
                    || str_contains(strtolower($item->action ?? ''), $searchTerm)
                    || str_contains(strtolower($item->type ?? ''), $searchTerm)
                    || str_contains(strtolower($item->responce ?? ''), $searchTerm);
            });
        }

        if ($this->filterReference) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->reference ?? ''), strtolower($this->filterReference));
            });
        }

        if ($this->filterSource) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->send ?? ''), strtolower($this->filterSource));
            });
        }

        if ($this->filterReceiver) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->receiver ?? ''), strtolower($this->filterReceiver));
            });
        }

        if ($this->filterActions) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->action ?? ''), strtolower($this->filterActions));
            });
        }

        if ($this->filterDate) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->date ?? ''), strtolower($this->filterDate));
            });
        }

        if ($this->filterType) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->type ?? ''), strtolower($this->filterType));
            });
        }

        if ($this->filterResponse) {
            $notifications = $notifications->filter(function ($item) {
                return str_contains(strtolower($item->responce ?? ''), strtolower($this->filterResponse));
            });
        }

        // Convert to paginator with proper Livewire support
        $perPage = (int)$this->pageCount;
        $currentPage = $this->getPage('page');
        $total = $notifications->count();
        $items = $notifications->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

    public function render()
    {
        return view('livewire.notification-history', [
            'notifications' => $this->notifications
        ])->extends('layouts.master')->section('content');
    }
}
