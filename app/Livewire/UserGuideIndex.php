<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserGuide;
use App\Services\UserGuide\UserGuideService;

class UserGuideIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = null;
    protected $queryString = ['search'];
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function delete()
    {
        if ($this->deleteId) {
            $this->userGuideService->delete($this->deleteId);
            session()->flash('success', __('User guide deleted successfully.'));
            $this->deleteId = null;
            $this->resetPage();
            $this->dispatchBrowserEvent('hide-delete-modal');
        }
    }

    public function render()
    {
        $userGuides = $this->userGuideService->getPaginated($this->search, 10);
        return view('livewire.user-guide-index', compact('userGuides'))->extends('layouts.master')->section('content');
    }
}
