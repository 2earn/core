<?php

namespace App\Http\Livewire;

use Core\Services\settingsManager;
use Livewire\Component;

class NotificationHistory extends Component
{

    public function mount(settingsManager $settingsManager,)
    {
        $history = $settingsManager->getHistory();
    }

    public function render()
    {
        return view('livewire.notification-history')->extends('layouts.master')->section('content');
    }
}
