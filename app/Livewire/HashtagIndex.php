<?php

namespace App\Livewire;

use App\Services\Hashtag\HashtagService;
use Livewire\Component;
use Livewire\WithPagination;

class HashtagIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDelete = false;
    public $deleteId = null;

    protected HashtagService $hashtagService;

    public function boot(HashtagService $hashtagService)
    {
        $this->hashtagService = $hashtagService;
    }

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
        $result = $this->hashtagService->deleteHashtag($this->deleteId);

        if ($result) {
            session()->flash('success', __('Hashtag deleted successfully.'));
        } else {
            session()->flash('danger', __('Error deleting hashtag.'));
        }

        $this->deleteId = null;
        $this->confirmingDelete = false;
    }

    public function render()
    {
        $hashtags = $this->hashtagService->getHashtags([
            'search' => $this->search,
            'order_by' => 'id',
            'order_direction' => 'desc',
            'PAGE_SIZE' => 4
        ]);

        return view('livewire.hashtag-index', compact('hashtags'))->extends('layouts.master')->section('content');
    }
}
