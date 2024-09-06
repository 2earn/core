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
    public $oldSurveyResponses;
    public $oldReponses = null;

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $survey = Survey::findOrFail($this->idSurvey);
        if ($survey->question?->selection == Selection::MULTIPLE->value) {
            $this->responces = [];
        }
        $this->initializeResponse($survey);
    }

    public function initializeResponse($survey)
    {
        $this->oldReponses = SurveyResponse::where('user_id', auth()->user()->id)->where('survey_id', $this->idSurvey)->first();
        if (!is_null($this->oldReponses)) {
            $this->oldSurveyResponses = SurveyResponseItem::where('surveyResponse_id', $this->oldReponses->id)->get();
            foreach ($survey->question->serveyQuestionChoice as $choice) {
                foreach ($this->oldSurveyResponses as $responce) {
                    if ($survey->question->id == $responce->surveyQuestion_id && $choice->id == $responce->surveyQuestionChoice_id) {
                        $this->responces[] = $choice->id;
                    }
                }
            }
        }
    }

    public function checkParticipation($survey, $question)
    {
        if ($survey->question->selection == Selection::MULTIPLE->value && !count($this->responces)) {
            throw new \Exception(Lang::get('Invalid responce: no selected choices'));
        }

        if ($survey->question->selection == Selection::UNIQUE->value && empty($this->responces)) {
            throw new \Exception(Lang::get('Invalid responce: no selected choices'));
        }

        if ($question->selection == Selection::UNIQUE->value && empty($this->responces)) {
            throw new \Exception(Lang::get('Invalid responce: too many selected choices'));
        }

        if ($question->selection == Selection::MULTIPLE->value && count($this->responces) > $question->maxResponse) {
            throw new \Exception(Lang::get('Invalid responce: too many selected choices'));
        }
    }

    public function createNewParicipationAndCheckLimits()
    {
        $surveyResponseParams = ['survey_id' => $this->idSurvey, 'user_id' => auth()->user()->id];
        $survey = Survey::findOrFail($this->idSurvey);

        if ($survey->getChronoAttchivement() == 100) {
            Survey::close($survey->id);
            throw new \Exception(Lang::get('Date limit ratcheted'));
        }

        $surveyResponse = SurveyResponse::create($surveyResponseParams);

        if (!is_null($survey->goals)) {
            if (SurveyResponse::where('survey_id', $this->idSurvey)->count() >= $survey->goals) {
                Survey::close($survey->id);
                throw new \Exception(Lang::get('Gools limit ratcheted'));
            }
        }

        return $surveyResponse;
    }

    public function participate()
    {
        try {
            $survey = Survey::findOrFail($this->idSurvey);
            $question = $survey->question;
            $this->checkParticipation($survey, $question);

            if (!is_null($this->oldReponses)) {
                $surveyResponse = $this->oldReponses;
            } else {
                $surveyResponse = $this->createNewParicipationAndCheckLimits();
            }

            SurveyResponseItem::where('surveyResponse_id', $surveyResponse->id)->where('surveyQuestion_id', $survey->question->id)->delete();

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
            return redirect()->route('survey_participate', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while participating to this survey!!') );
        }
       return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('You just participated successfully to this survey'));

    }

    public function render()
    {
        $params['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-paricipate', $params)->extends('layouts.master')->section('content');
    }
}
