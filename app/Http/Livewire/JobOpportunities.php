<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class JobOpportunities extends Component
{
    public $deadline;

    public function mount()
    {
        $this->deadline = DB::table('settings')->where('ParameterName', 'Job_opportunity_cs')->value('StringValue');
    }
    public function render()
    {
        return view('livewire.job-opportunities')->extends('layouts.master')->section('content');
    }
}
