<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserGuide;

class UserGuideIndex extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
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
