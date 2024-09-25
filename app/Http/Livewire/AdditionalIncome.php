<?php

namespace App\Http\Livewire;

use App\Models\CommittedInvestorRequest;
use App\Models\InstructorRequest;
use App\Models\SoldesView;
use Core\Enum\RequestStatus;
use Core\Enum\StatusRequest;
use Illuminate\Support\Facades\DB;
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
            $lastInstructorRequest = InstructorRequest::where('user_id', auth()->user()->id)
                ->where('status', RequestStatus::InProgress->value)
                ->orderBy('created_at', 'DESC')->first();

            if (is_null($lastInstructorRequest)) {
                InstructorRequest::Create([
                    'user_id' => auth()->user()->id,
                    'request_date' => now(),
                    'status' => RequestStatus::InProgress->value
                ]);
                return redirect()->route('business_hub_additional_income', app()->getLocale())->with('success', trans('Your instructor request is sent'));
            } else {
                return redirect()->route('business_hub_additional_income', app()->getLocale())->with('warning', trans('You have one instructor request under reviewing'));
            }
        }

    }

    public function sendCommitedInvestorRequest()
    {

        if ($this->isCommitedInvestor) {
            $lastCommittedInvestorRequest = CommittedInvestorRequest::where('user_id', auth()->user()->id)
                ->where('status', RequestStatus::InProgress->value)
                ->orderBy('created_at', 'DESC')->first();

            if (is_null($lastCommittedInvestorRequest)) {
                CommittedInvestorRequest::Create([
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
        $param = DB::table('settings')->where("ParameterName", "=", "BE_COMMITED_INVESTOR")->first();
        if (!is_null($param)) {
            return $param->IntegerValue;
        } else {
            return self::BE_COMMITED_INVESTOR;
        }
    }

    public function render()
    {
        $soldes = SoldesView::where('id', auth()->user()->id)->first();
        $soldesAction = is_null($soldes->action) ? 0 : $soldes->action;
        $beCommitedInvestorMinActions = $this->getBeCommitedInvestorMinActions();

        $lastCommittedInvestorRequest = CommittedInvestorRequest::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();
        $lastInstructorRequest = InstructorRequest::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();


        $validatedUser = in_array(auth()->user()->status, [StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value, StatusRequest::ValidNational->value]);

        if (!is_null($lastCommittedInvestorRequest) && ($lastCommittedInvestorRequest->status == RequestStatus::InProgress->value || $lastCommittedInvestorRequest->status == RequestStatus::Validated->value)) {
            $this->isCommitedInvestor = true;
        }

        if ($lastCommittedInvestorRequest?->status == RequestStatus::InProgress->value || $lastCommittedInvestorRequest?->status == RequestStatus::Validated->value || $soldesAction < $beCommitedInvestorMinActions) {
            $this->isCommitedInvestorDisabled = true;
        }

        if (!is_null($lastInstructorRequest) && ($lastInstructorRequest->status == RequestStatus::InProgress->value || $lastInstructorRequest->status == RequestStatus::Validated->value)) {
            $this->isInstructor = true;
        }

        if (!$validatedUser || $lastInstructorRequest?->status == RequestStatus::Validated->value || $lastInstructorRequest->status == RequestStatus::InProgress->value) {
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
