<?php

namespace App\Livewire;

use App\Services\CommunicationBoardService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommunicationBoard extends Component
{
    protected CommunicationBoardService $communicationBoardService;

    public $communicationBoard = [];
    public $currentRouteName;

    public function boot(CommunicationBoardService $communicationBoardService)
    {
        $this->communicationBoardService = $communicationBoardService;
    }

    public function mount()
    {
        $this->communicationBoard = $this->communicationBoardService->getCommunicationBoardItems();
        $this->currentRouteName = Route::currentRouteName();
    }

    public function render()
    {
        $params = [];
        return view('livewire.communication-board', $params)->extends('layouts.master')->section('content');
    }
}
