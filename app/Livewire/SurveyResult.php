<?php

namespace App\Livewire;

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
    public $showDetail;

    public function mount($idSurvey, $showDetail = false)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
        $this->showDetail = $showDetail;
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        $params ['question'] = SurveyQuestion::find($params ['survey']->question->id);
        $params ['responses'] = $params ['question']->serveyQuestionChoice()->get();
        $participation = SurveyResponse::where('survey_id', $this->idSurvey)->count();
        $stats = [];
        $totalChoosen = 0;
        $totalParticipation = 0;
        $totalChoiceChoosen = SurveyResponseItem::where('surveyQuestion_id', $params ['survey']->question->id)->count();

        foreach ($params ['responses'] as $response) {
            $choosen = SurveyResponseItem::where('surveyQuestion_id', $params ['survey']->question->id)
                ->where('surveyQuestionChoice_id', $response->id)->count();

            $stats[$response->id] = [
                'title' => TranslaleModel::getTranslation($response, 'title', $response->title),
                'choosen' => $choosen . ' / ' . $participation,
                'choosenK' => $choosen . ' / ' . $totalChoiceChoosen,
                'persontage' => $participation > 0 ? (($choosen / $participation) * 100) : 0,
                'persontageK' => $participation > 0 ? (($choosen / $totalChoiceChoosen) * 100) : 0
            ];
            $totalChoosen += $choosen;
            $totalParticipation += $participation;
        }
        $params['stats'] = $stats;
        $params['totalChoosen'] = $totalChoosen;
        $params['participation'] = $participation;

        return view('livewire.survey-result', $params)->extends('layouts.master')->section('content');
    }
}
