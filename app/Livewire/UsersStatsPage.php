<?php

namespace App\Livewire;

use Livewire\Component;

class UsersStatsPage extends Component
{
    public function render()
    {
        return view('livewire.users-stats-page')
            ->extends('layouts.master')
            ->section('content');
    }
}

