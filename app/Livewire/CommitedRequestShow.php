<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\CommittedInvestor\CommittedInvestorRequestService;
use App\Services\UserService;
use Core\Enum\RequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommitedRequestShow extends Component
{
    protected CommittedInvestorRequestService $committedInvestorRequestService;
    protected UserService $userService;

    public $rejectOpened = false;
    public $note;
    public $note_message;
    public $userProfileImage;
    public $CommitedRequestId;

    public function boot(CommittedInvestorRequestService $committedInvestorRequestService, UserService $userService)
    {
        $this->committedInvestorRequestService = $committedInvestorRequestService;
        $this->userService = $userService;
    }

    public function mount()
    {
        $this->CommitedRequestId = Route::current()->parameter('id');
        $committedInvestorRequest = $this->committedInvestorRequestService->getCommittedInvestorRequestById($this->CommitedRequestId);
        $this->userProfileImage = User::getUserProfileImage($committedInvestorRequest->user->idUser);
    }

    public function validateRequest()
    {
        $committedInvestorRequest = $this->committedInvestorRequestService->getCommittedInvestorRequestById($this->CommitedRequestId);
        $this->committedInvestorRequestService->updateCommittedInvestorRequest(
            $this->CommitedRequestId,
            [
                'status' => RequestStatus::Validated->value,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]
        );
        $this->userService->updateById($committedInvestorRequest->user_id, ['commited_investor' => true]);
        return redirect()->route('requests_commited_investors', app()->getLocale())->with('success', trans('Committed investor request is validated'));
    }

    public function initRejectRequest()
    {
        $this->rejectOpened = true;
    }

    public function rejectRequest()
    {
        if (!empty($this->note) && !is_null($this->note)) {
            $this->committedInvestorRequestService->updateCommittedInvestorRequest(
                $this->CommitedRequestId,
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
            'commitedInvestorsRequests' => $this->committedInvestorRequestService->getUserCommittedInvestorRequests(auth()->user()->id),
            'commitedInvestorsRequest' => $this->committedInvestorRequestService->getCommittedInvestorRequestById($this->CommitedRequestId)
        ];
        return view('livewire.commited-request-show', $params)->extends('layouts.master')->section('content');
    }
}
