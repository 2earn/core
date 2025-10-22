<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserGuide;

class UserGuideIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = null;
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        // dispatch a browser event so JS listeners can open the modal
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function delete()
    {
        if ($this->deleteId) {
            $guide = UserGuide::findOrFail($this->deleteId);
            $guide->delete();
            session()->flash('success', __('User guide deleted successfully.'));
            $this->deleteId = null;
            $this->resetPage();
            // hide the modal in the browser
            $this->dispatchBrowserEvent('hide-delete-modal');
        }
    }

    public function render()
    {
        $userGuides = UserGuide::with('user')
            ->where(function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);
        return view('livewire.user-guide-index', compact('userGuides'))->extends('layouts.master')->section('content');
    }
}
