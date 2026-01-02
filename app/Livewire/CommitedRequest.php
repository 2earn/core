<?php

namespace App\Livewire;

use App\Services\CommittedInvestor\CommittedInvestorRequestService;
use Livewire\Component;

class CommitedRequest extends Component
{
    protected CommittedInvestorRequestService $committedInvestorRequestService;

    public function boot(CommittedInvestorRequestService $committedInvestorRequestService)
    {
        $this->committedInvestorRequestService = $committedInvestorRequestService;
    }

    public function render()
    {
        $params = [
            'commitedInvestorsRequests' => $this->committedInvestorRequestService->getInProgressRequests()
        ];
        return view('livewire.commited-request', $params)->extends('layouts.master')->section('content');
    }
}
