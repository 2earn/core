<?php

namespace App\Livewire;

use App\Enums\Selection;
use App\Notifications\SurveyParticipation;
use App\Services\SurveyResponseItemService;
use App\Services\SurveyResponseService;
use App\Services\SurveyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyParicipate extends Component
{

    public $idSurvey;
    public $currentRouteName;
    public $particiapetionProcess = false;
    public $routeRedirectionParams;
    public $responces;
    public $oldSurveyResponses;
    public $oldReponses = null;
    public $showDetail;

    protected SurveyService $surveyService;
    protected SurveyResponseService $surveyResponseService;
    protected SurveyResponseItemService $surveyResponseItemService;

    public function boot(
        SurveyService $surveyService,
        SurveyResponseService $surveyResponseService,
        SurveyResponseItemService $surveyResponseItemService
    ) {
        $this->surveyService = $surveyService;
        $this->surveyResponseService = $surveyResponseService;
        $this->surveyResponseItemService = $surveyResponseItemService;
    }

    public function mount($idSurvey, $showDetail = false)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $survey = $this->surveyService->findOrFail($this->idSurvey);
        if ($survey->question?->selection == Selection::MULTIPLE->value) {
            $this->responces = [];
        }
        $this->initializeResponse($survey);
        $this->showDetail = $showDetail;
    }

    public function initializeResponse($survey)
    {
        $this->oldReponses = $this->surveyResponseService->getByUserAndSurvey(
            auth()->user()->id,
            $this->idSurvey
        );

        if (!is_null($this->oldReponses)) {
            $this->oldSurveyResponses = $this->surveyResponseItemService->getBySurveyResponse(
                $this->oldReponses->id
            );

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
            throw new \Exception(Lang::get('Invalid response: no selected choices'));
        }

        if ($survey->question->selection == Selection::UNIQUE->value && empty($this->responces)) {
            throw new \Exception(Lang::get('Invalid response: no selected choices'));
        }

        if ($question->selection == Selection::UNIQUE->value && empty($this->responces)) {
            throw new \Exception(Lang::get('Invalid response: too many selected choices'));
        }

        if ($question->selection == Selection::MULTIPLE->value && count($this->responces) > $question->maxResponse) {
            throw new \Exception(Lang::get('Invalid response: too many selected choices'));
        }
    }

    public function createNewParicipationAndCheckLimits()
    {
        $surveyResponseParams = ['survey_id' => $this->idSurvey, 'user_id' => auth()->user()->id];
        $survey = $this->surveyService->findOrFail($this->idSurvey);

        if ($survey->getChronoAttchivement() == 100) {
            $this->surveyService->close($survey->id);
            throw new \Exception(Lang::get('Date limit ratcheted'));
        }

        $surveyResponse = $this->surveyResponseService->create($surveyResponseParams);

        if (!is_null($survey->goals)) {
            if ($this->surveyResponseService->countBySurvey($this->idSurvey) >= $survey->goals) {
                $this->surveyService->close($survey->id);
                throw new \Exception(Lang::get('Gools limit ratcheted'));
            }
        }

        return $surveyResponse;
    }

    public function participate()
    {
        try {
            $survey = $this->surveyService->findOrFail($this->idSurvey);
            $question = $survey->question;
            $this->checkParticipation($survey, $question);

            if (!is_null($this->oldReponses)) {
                $surveyResponse = $this->oldReponses;
            } else {
                $surveyResponse = $this->createNewParicipationAndCheckLimits();
            }

            if (!$this->particiapetionProcess) {
                $this->particiapetionProcess = true;

                if ($this->surveyResponseItemService->countByResponseAndQuestion(
                    $surveyResponse->id,
                    $survey->question->id
                ) > 1) {
                    return redirect()->route('surveys_participate', $this->routeRedirectionParams)
                        ->with('danger', Lang::get('Many participation to this survey'));
                }

                $this->surveyResponseItemService->deleteByResponseAndQuestion(
                    $surveyResponse->id,
                    $survey->question->id
                );

                if ($question->selection == Selection::MULTIPLE->value) {
                    $this->surveyResponseItemService->createMultiple(
                        $surveyResponse->id,
                        $survey->question->id,
                        $this->responces
                    );
                } else {
                    $this->surveyResponseItemService->create([
                        'surveyResponse_id' => $surveyResponse->id,
                        'surveyQuestion_id' => $survey->question->id,
                        'surveyQuestionChoice_id' => $this->responces
                    ]);
                }

                $this->particiapetionProcess = false;
            }

            Auth::user()->notify(new SurveyParticipation($survey));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_participate', $this->routeRedirectionParams)
                ->with('danger', Lang::get('Something goes wrong while participating to this survey'));
        }

        return redirect()->route('surveys_show', $this->routeRedirectionParams)
            ->with('success', Lang::get('You just participated successfully to this survey'));
    }

    public function render()
    {
        $params['survey'] = $this->surveyService->findOrFail($this->idSurvey);
        return view('livewire.survey-paricipate', $params)->extends('layouts.master')->section('content');
    }
}
