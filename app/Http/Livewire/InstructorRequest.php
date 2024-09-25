<?php

namespace App\Http\Livewire;

use App\Models\InstructorRequest as InstructorRequestModel;
use Core\Enum\RequestStatus;
use Livewire\Component;

class InstructorRequest extends Component
{

    public function render()
    {
        $params = ['instructorRequests' => InstructorRequestModel::where('status', RequestStatus::InProgress->value)->get()];
        return view('livewire.instructor', $params)->extends('layouts.master')->section('content');
    }
}
