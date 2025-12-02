<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use App\Services\CommittedInvestor\CommittedInvestorRequestService;
use App\Services\InstructorRequest\InstructorRequestService;
use App\Services\Settings\SettingsService;
use App\Models\User;
use Core\Enum\RequestStatus;
use Core\Enum\BeInstructorRequestStatus;
use Core\Enum\StatusRequest;
use Livewire\Component;

class AdditionalIncome extends Component
{
    public $isCommitedInvestor = false;
    public $isInstructor = false;
    public $isCommitedInvestorDisabled = false;
    public $isInstructorDisabled = false;
    const BE_COMMITED_INVESTOR = 1000;

    protected $listeners = [
        'sendCommitedInvestorRequest' => 'sendCommitedInvestorRequest'
    ];

    public function sendInstructorRequest()
    {
        if ($this->isInstructor) {
            $instructorRequestService = app(InstructorRequestService::class);

            $lastInstructorRequest = $instructorRequestService->getLastInstructorRequestByStatus(
                auth()->user()->id,
                BeInstructorRequestStatus::InProgress->value
            );

            if (is_null($lastInstructorRequest)) {
                $instructorRequest = $instructorRequestService->createInstructorRequest([
                    'user_id' => auth()->user()->id,
                    'request_date' => now(),
                    'status' => BeInstructorRequestStatus::InProgress->value
                ]);

                if ($instructorRequest) {
                    User::find($instructorRequest->user_id)->update(['instructor' => BeInstructorRequestStatus::InProgress->value]);
                    return redirect()->route('business_hub_additional_income', app()->getLocale())->with('success', trans('Your instructor request is sent'));
                }
            } else {
                return redirect()->route('business_hub_additional_income', app()->getLocale())->with('warning', trans('You have one instructor request under reviewing'));
            }
        }

    }

    public function sendCommitedInvestorRequest()
    {
        if ($this->isCommitedInvestor) {
            $committedInvestorRequestService = app(CommittedInvestorRequestService::class);

            $lastCommittedInvestorRequest = $committedInvestorRequestService->getLastCommittedInvestorRequestByStatus(
                auth()->user()->id,
                RequestStatus::InProgress->value
            );

            if (is_null($lastCommittedInvestorRequest)) {
                $committedInvestorRequestService->createCommittedInvestorRequest([
                    'user_id' => auth()->user()->id,
                    'request_date' => now(),
                    'status' => RequestStatus::InProgress->value
                ]);
                return redirect()->route('business_hub_additional_income', app()->getLocale())->with('success', trans('Your committed investor request is sent'));
            } else {
                return redirect()->route('business_hub_additional_income', app()->getLocale())->with('warning', trans('You have one committed investor request under reviewing'));
            }
        }

    }

    public function getBeCommitedInvestorMinActions()
    {
        return app(SettingsService::class)->getIntegerParameter('BE_COMMITED_INVESTOR', self::BE_COMMITED_INVESTOR);
    }

    public function render()
    {
        $instructorRequestService = app(InstructorRequestService::class);
        $committedInvestorRequestService = app(CommittedInvestorRequestService::class);

        $soldesAction = is_null(Balances::getStoredUserBalances(auth()->user()->idUser, Balances::SHARE_BALANCE)) ? 0 : Balances::getStoredUserBalances(auth()->user()->idUser, Balances::SHARE_BALANCE);
        $beCommitedInvestorMinActions = $this->getBeCommitedInvestorMinActions();

        $lastCommittedInvestorRequest = $committedInvestorRequestService->getLastCommittedInvestorRequest(auth()->user()->id);
        $lastInstructorRequest = $instructorRequestService->getLastInstructorRequest(auth()->user()->id);

        $validatedUser = in_array(auth()->user()->status, [StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value, StatusRequest::ValidNational->value]);

        if (auth()->user()->commited_investor == true || (!is_null($lastCommittedInvestorRequest) && $lastCommittedInvestorRequest->status == RequestStatus::InProgress->value)) {
            $this->isCommitedInvestor = true;
        }

        if ($lastCommittedInvestorRequest?->status == RequestStatus::InProgress->value || auth()->user()->commited_investor == true || $soldesAction < $beCommitedInvestorMinActions) {
            $this->isCommitedInvestorDisabled = true;
        }

        if (auth()->user()->instructor == BeInstructorRequestStatus::Validated->value ||auth()->user()->instructor == BeInstructorRequestStatus::Validated2earn->value || (!is_null($lastInstructorRequest) && ($lastInstructorRequest->status == BeInstructorRequestStatus::InProgress->value))) {
            $this->isInstructor = true;
        }

        if (!$validatedUser || auth()->user()->instructor == BeInstructorRequestStatus::Validated2earn->value ||auth()->user()->instructor == BeInstructorRequestStatus::Validated->value || $lastInstructorRequest?->status == BeInstructorRequestStatus::InProgress->value) {
            $this->isInstructorDisabled = true;
        }


        $params = [
            'beCommitedInvestorMinActions' => $beCommitedInvestorMinActions,
            'soldesAction' => $soldesAction,
            'lastCommittedInvestorRequest' => $lastCommittedInvestorRequest,
            'validatedUser' => $validatedUser,
            'lastInstructorRequest' => $lastInstructorRequest,
        ];

        return view('livewire.additional-income', $params)->extends('layouts.master')->section('content');
    }
}
