<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyResponseItem;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyParicipate extends Component
{

    public $idSurvey;
    public $currentRouteName;
    public $responces = [];

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
    }

    public function participate()
    {
        $surveyResponse = SurveyResponse::create(
            [
                'survey_id' => $this->idSurvey,
                'user_id' => auth()->user()->id
            ]
        );
        SurveyResponseItem::create([
            'surveyResponse_id'=> $surveyResponse
        ]);
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-paricipate', $params)->extends('layouts.master')->section('content');
    }
}
