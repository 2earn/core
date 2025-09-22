<?php

namespace App\Livewire;

use App\Models\Hashtag;
use Livewire\Component;
use Livewire\WithPagination;

class HashtagIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $hashtag = Hashtag::findOrFail($id);
        $hashtag->delete();
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
