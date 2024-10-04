<?php

namespace App\Http\Livewire;

use App\Models\CommittedInvestorRequest;
use Core\Enum\RequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommitedRequest extends Component
{

    public function render()
    {
        $params = ['commitedInvestorsRequests' => CommittedInvestorRequest::where('status', RequestStatus::InProgress->value)->get()];
        return view('livewire.commited-request', $params)->extends('layouts.master')->section('content');
    }
}
