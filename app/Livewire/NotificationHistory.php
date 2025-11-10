<?php

namespace App\Livewire;

use Core\Services\settingsManager;
use Livewire\Component;

class NotificationHistory extends Component
{
    public function render()
    {
        return view('livewire.notification-history')->extends('layouts.master')->section('content');
    }
}
