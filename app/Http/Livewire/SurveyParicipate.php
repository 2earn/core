<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyResponseItem;
use Core\Enum\Selection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyParicipate extends Component
{

    public $idSurvey;
    public $currentRouteName;
    public $responces;

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $survey = Survey::findOrFail($this->idSurvey);
        if ($survey->question?->selection == Selection::MULTIPLE->value) {
            $this->responces = [];
        }
    }

    public function participate()
    {
        try {
            $survey = Survey::findOrFail($this->idSurvey);
            $question = $survey->question;

            if ($survey->question->selection == Selection::MULTIPLE->value && !count($this->responces)) {
                return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Invalid responce: no selected choices'));
            }

            if ($survey->question->selection == Selection::UNIQUE->value && empty($this->responces)) {
                return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Invalid responce: no selected choices'));
            }

            if ($question->selection == Selection::UNIQUE->value && empty($this->responces)) {
                return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Invalid responce: too many selected choices'));
            }

            if ($question->selection == Selection::MULTIPLE->value && count($this->responces) > $question->maxResponse) {
                return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Invalid responce: too many selected choices'));
            }

            $surveyResponseParams = ['survey_id' => $this->idSurvey, 'user_id' => auth()->user()->id];
            $surveyResponse = SurveyResponse::create($surveyResponseParams);

            if ($question->selection == Selection::MULTIPLE->value) {
                foreach ($this->responces as $responceItem) {
                    $surveyResponseItemParams = ['surveyResponse_id' => $surveyResponse->id, 'surveyQuestion_id' => $survey->question->id, 'surveyQuestionChoice_id' => $responceItem];
                    SurveyResponseItem::create($surveyResponseItemParams);
                }
            } else {
                $surveyResponseItemParams = ['surveyResponse_id' => $surveyResponse->id, 'surveyQuestion_id' => $survey->question->id, 'surveyQuestionChoice_id' => $this->responces];
                SurveyResponseItem::create($surveyResponseItemParams);
            }

        } catch (\Exception $exception) {
            return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while participating to this survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('You just participated successfully to this survey'));

    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-paricipate', $params)->extends('layouts.master')->section('content');
    }
}
