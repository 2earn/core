<?php

namespace App\Http\Livewire;

use App\Models\SoldesView;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdditionalIncome extends Component
{
    public $canBeCommitedInvestor = false;
    public $isCommitedInvestor = false;
    const BE_COMMITED_INVESTOR = 1000;

    public function render()
    {
        $param = DB::table('settings')->where("ParameterName", "=", "BE_COMMITED_INVESTOR")->first();
        if (!is_null($param) && !is_null($param)) {
            $beCommitedInvestor = $param->IntegerValue;
        } else {
            $beCommitedInvestor = self::BE_COMMITED_INVESTOR;
        }

        $soldes = SoldesView::where('id', auth()->user()->id)->first();
        if (!is_null($soldes->action) && $soldes->action > $beCommitedInvestor) {
            $this->canBeCommitedInvestor = true;
        }
        $params = [
            'canBeCommitedInvestor' => $this->canBeCommitedInvestor,
            'beCommitedInvestor' => $beCommitedInvestor
        ];
        return view('livewire.additional-income', $params)->extends('layouts.master')->section('content');
    }
}
