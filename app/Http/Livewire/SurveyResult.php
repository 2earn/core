<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyResult extends Component
{

    public $idSurvey;
    public $currentRouteName;

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-result', $params)->extends('layouts.master')->section('content');
    }
}
