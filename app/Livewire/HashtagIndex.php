<?php

namespace App\Livewire;

use App\Models\Hashtag;
use Livewire\Component;
use Livewire\WithPagination;

class HashtagIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = false;
    public $deleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->confirmingDelete = false;
    }

    public function deleteConfirmed()
    {
        $hashtag = Hashtag::findOrFail($this->deleteId);
        $hashtag->delete();
        $this->deleteId = null;
        $this->confirmingDelete = false;
        session()->flash('success', __('Hashtag deleted successfully.'));
    }

    public function render()
    {
        $hashtags = Hashtag::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.hashtag-index', compact('hashtags'))->extends('layouts.master')->section('content');
    }
}
