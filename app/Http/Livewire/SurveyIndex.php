<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SurveyIndex extends Component
{
    public function render()
    {
        $params = [];
        return view('livewire.survey-index', ["params" => $params])->extends('layouts.master')->section('content');
    }
}
