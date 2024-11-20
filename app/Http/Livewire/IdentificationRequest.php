<?php

namespace App\Http\Livewire;

use Core\Enum\StatusRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IdentificationRequest extends Component
{
    public function render()
    {
        $identificationRequests = DB::select(getSqlFromPath('identification_request'), [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value]);
        return view('livewire.identification-request', ['identificationRequests' => $identificationRequests])->extends('layouts.master')->section('content');
    }
}
