<?php

namespace App\Livewire;

use App\Services\InstructorRequestService;
use Livewire\Component;

class InstructorRequest extends Component
{
    protected InstructorRequestService $instructorRequestService;

    public function boot(InstructorRequestService $instructorRequestService)
    {
        $this->instructorRequestService = $instructorRequestService;
    }

    public function render()
    {
        $instructorRequests = $this->instructorRequestService->getInProgressRequests();

        return view('livewire.instructor', ['instructorRequests' => $instructorRequests])
            ->extends('layouts.master')
            ->section('content');
    }
}
