<?php

namespace App\Livewire;

use App\Services\Communication\Communication;
use App\Services\EventService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class EventIndex extends Component
{
    use WithPagination;

    protected EventService $eventService;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $eventIdToDelete = null;
    public $listeners = ['delete' => 'delete', 'clearDeleteEventId' => 'clearDeleteEventId'];

    public function boot(EventService $eventService)
    {
        $this->eventService = $eventService;
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

    public function confirmDelete($id)
    {
        $this->eventIdToDelete = $id;
        $this->dispatch('showDeleteModal');
    }

    public function delete()
    {
        try {
            $this->eventService->delete($this->eventIdToDelete);
            $this->eventIdToDelete = null;
            $this->dispatch('hideDeleteModal');
            return redirect()->route('event_index', ['locale' => app()->getLocale()])
                ->with('success', Lang::get('Event deleted successfully'));
        } catch (\Exception $exception) {
            $this->dispatch('hideDeleteModal');
            return redirect()->route('event_index', ['locale' => app()->getLocale()])
                ->with('error', Lang::get('Event deletion failed'));
        }
    }

    public function clearDeleteEventId()
    {
        $this->eventIdToDelete = null;
    }

    public function duplicate($id)
    {
        try {
            Communication::duplicateEvent($id);
            return redirect()->route('event_index', ['locale' => app()->getLocale()])
                ->with('success', __('Event duplicated successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('event_index', ['locale' => app()->getLocale()])
                ->with('error', __('Event duplication failed'));
        }
    }

    public function render()
    {
        $events = $this->eventService->getPaginatedWithCounts($this->search, self::PAGE_SIZE);
        return view('livewire.event-index', compact('events'))
            ->extends('layouts.master')
            ->section('content');
    }
}
