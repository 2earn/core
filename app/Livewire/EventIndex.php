<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class EventIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['delete' => 'delete'];

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

    public function delete($id)
    {
        try {
            Event::destroy($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('event_index', ['locale' => app()->getLocale()])->with('error', Lang::get('Event deletion failed'));
        }
        return redirect()->route('event_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Event deleted successfully'));
    }

    public function render()
    {
        $events = Event::where('title', 'like', "%{$this->search}%")
            ->orderByDesc('published_at')
            ->paginate(self::PAGE_SIZE);
        return view('livewire.event-index', compact('events'))->extends('layouts.master')->section('content');
    }
}
