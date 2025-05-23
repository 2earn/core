<?php

namespace App\Livewire;

use App\Models\CommittedInvestorRequest;
use App\Models\User;
use Core\Enum\RequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommitedRequestShow extends Component
{
    public $rejectOpened = false;
    public $note;
    public $note_message;
    public $userProfileImage;

    public function mount()
    {
        $this->CommitedRequestId = Route::current()->parameter('id');
        $committedInvestorRequest = CommittedInvestorRequest::find($this->CommitedRequestId);
        $this->userProfileImage = User::getUserProfileImage($committedInvestorRequest->user->idUser);
    }

    public function validateRequest()
    {
        $committedInvestorRequest = CommittedInvestorRequest::find($this->CommitedRequestId);
        $committedInvestorRequest->update(
            [
                'status' => RequestStatus::Validated->value,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]);
        User::find($committedInvestorRequest->user_id)->update(['commited_investor' => true]);
        return redirect()->route('requests_commited_investors', app()->getLocale())->with('success', trans('Committed investor request is validated'));

    }

    public function initRejectRequest()
    {
        $this->rejectOpened = true;
    }

    public function rejectRequest()
    {
        if (!empty($this->note) && !is_null($this->note)) {
            $committedInvestorRequest = CommittedInvestorRequest::find($this->CommitedRequestId);
            $committedInvestorRequest->update(
                [
                    'status' => RequestStatus::Rejected->value,
                    'examination_date' => now(),
                    'note' => $this->note,
                    'examiner_id' => auth()->user()->id,
                ]
            );
            return redirect()->route('requests_commited_investors', app()->getLocale())->with('warning', trans('Committed investor request is Rejected'));

        } else {
            $this->note_message = trans('Empty Rejection message');
        }
    }

    public function render()
    {

        $params = [
            'commitedInvestorsRequests' => CommittedInvestorRequest::where('user_id', auth()->user()->id)->get(),
            'commitedInvestorsRequest' => CommittedInvestorRequest::find($this->CommitedRequestId)
        ];
        return view('livewire.commited-request-show', $params)->extends('layouts.master')->section('content');
    }
}
