<?php

namespace App\Http\Livewire;

use App\Models\CommittedInvestorRequest;
use App\Models\User;
use Core\Enum\CommittedInvestorRequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommitedRequestShow extends Component
{
    public function mount()
    {
        $this->CommitedRequestId = Route::current()->parameter('id');
    }

    public function validateRequest()
    {
        $committedInvestorRequest = CommittedInvestorRequest::find($this->CommitedRequestId);
        $committedInvestorRequest->update(
            [
                'status' => CommittedInvestorRequestStatus::Validated->value,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]);
        User::find($committedInvestorRequest->user_id)->update(['commited_investor' => true]);
        return redirect()->route('commited_investors_requests', app()->getLocale())->with('success', trans('Committed investor request is validated'));

    }

    public function rejectRequest()
    {
        $committedInvestorRequest = CommittedInvestorRequest::find($this->CommitedRequestId);
        $committedInvestorRequest->update(
            [
                'status' => CommittedInvestorRequestStatus::Rejected->value,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]);
        return redirect()->route('commited_investors_requests', app()->getLocale())->with('warning', trans('Committed investor request is Rejected'));

    }

    public function render()
    {
        $params = ['commitedInvestorsRequest' => CommittedInvestorRequest::find($this->CommitedRequestId)];
        return view('livewire.commited-request-show', $params)->extends('layouts.master')->section('content');
    }
}
