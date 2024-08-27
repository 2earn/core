<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyResponseItem;
use App\Models\TranslaleModel;
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
        $params ['question'] = SurveyQuestion::find($params ['survey']->question->id);
        $params ['responses'] = $params ['question']->serveyQuestionChoice()->get();
        $participation = SurveyResponse::where('survey_id', $this->idSurvey)->count();
        $stats = [];
        foreach ($params ['responses'] as $response) {
            $choosen = SurveyResponseItem::where('surveyQuestion_id', $params ['survey']->question->id)
                ->where('surveyQuestionChoice_id', $response->id)->count();

            $stats[$response->id] = [
                'title' => TranslaleModel::getTranslation($response, 'title', $response->title),
                'choosen' => $choosen,
                'persontage' => $participation > 0 ? (($choosen / $participation) * 100) : 0
            ];
        }
        $params ['stats'] = $stats;
        return view('livewire.survey-result', $params)->extends('layouts.master')->section('content');
    }
}
