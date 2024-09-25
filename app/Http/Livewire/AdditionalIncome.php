<?php

namespace App\Http\Livewire;

use App\Models\CommittedInvestorRequest;
use App\Models\SoldesView;
use Core\Enum\CommittedInvestorRequestStatus;
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

    public function sendCommitedInvestorRequest()
    {

        if ($this->isCommitedInvestor) {
            $lastCommittedInvestorRequest = CommittedInvestorRequest::where('user_id', auth()->user()->id)
                ->where('status', CommittedInvestorRequestStatus::InProgress->value)
                ->orderBy('created_at', 'DESC')->first();

            if (is_null($lastCommittedInvestorRequest)) {
                CommittedInvestorRequest::Create([
                    'user_id' => auth()->user()->id,
                    'request_date' => now(),
                    'status' => CommittedInvestorRequestStatus::InProgress->value
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

        if (!is_null($lastCommittedInvestorRequest) && ($lastCommittedInvestorRequest->status == CommittedInvestorRequestStatus::InProgress->value || $lastCommittedInvestorRequest->status == CommittedInvestorRequestStatus::Validated->value)) {
            $this->isCommitedInvestor = true;
        }

        if ($lastCommittedInvestorRequest?->status == CommittedInvestorRequestStatus::InProgress->value || $lastCommittedInvestorRequest?->status == CommittedInvestorRequestStatus::Validated->value || $soldesAction < $beCommitedInvestorMinActions) {
            $this->isCommitedInvestorDisabled = true;
        }

        if (!in_array(auth()->user()->status, [StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value, StatusRequest::ValidNational->value,])) {
            $this->isInstructorDisabled = true;
        }

        $params = [
            'beCommitedInvestorMinActions' => $beCommitedInvestorMinActions,
            'soldesAction' => $soldesAction,
            'lastCommittedInvestorRequest' => $lastCommittedInvestorRequest,
        ];

        return view('livewire.additional-income', $params)->extends('layouts.master')->section('content');
    }
}
