<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserGuide;

class UserGuideIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $userGuides = UserGuide::with('user')->latest()->paginate(10);
        return view('livewire.user-guide-index', compact('userGuides'))->extends('layouts.master')->section('content');
    }
}
