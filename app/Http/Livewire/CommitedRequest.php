<?php

namespace App\Http\Livewire;

use App\Models\CommittedInvestorRequest;
use Core\Enum\CommittedInvestorRequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommitedRequest extends Component
{

    public function render()
    {
        $params = ['commitedRequestInvestorsRequests' => CommittedInvestorRequest::where('status', CommittedInvestorRequestStatus::InProgress->value)->get()];
        return view('livewire.commited-request', $params)->extends('layouts.master')->section('content');
    }
}
