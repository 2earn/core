<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyResponseItem;
use Illuminate\Support\Facades\Lang;
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
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
    }

    public function participate()
    {

        try {







        $survey = Survey::findOrFail($this->idSurvey);
        $surveyResponse = SurveyResponse::create(
            [
                'survey_id' => $this->idSurvey,
                'user_id' => auth()->user()->id
            ]
        );
        foreach ($this->responces as $responceItem) {
           $surveyResponseItem=     SurveyResponseItem::create([
                'surveyResponse_id' => $surveyResponse->id,
                'surveyQuestion_id' => $survey->question->id,
                'surveyQuestionChoice_id' => $responceItem,
            ]);
        }
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while participating to this survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('You just participated successfully to this survey'));

    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-paricipate', $params)->extends('layouts.master')->section('content');
    }
}
