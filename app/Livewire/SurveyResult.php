<?php

namespace App\Livewire;

use App\Services\Survey\SurveyResponseItemService;
use App\Services\Survey\SurveyResponseService;
use App\Services\Survey\SurveyQuestionService;
use App\Services\Survey\SurveyService;
use App\Services\TranslaleModelService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyResult extends Component
{

    public $idSurvey;
    public $currentRouteName;
    public $showDetail;

    protected SurveyService $surveyService;
    protected SurveyQuestionService $questionService;
    protected SurveyResponseService $responseService;
    protected SurveyResponseItemService $responseItemService;
    protected TranslaleModelService $translationService;

    public function boot(
        SurveyService $surveyService,
        SurveyQuestionService $questionService,
        SurveyResponseService $responseService,
        SurveyResponseItemService $responseItemService,
        TranslaleModelService $translationService
    ) {
        $this->surveyService = $surveyService;
        $this->questionService = $questionService;
        $this->responseService = $responseService;
        $this->responseItemService = $responseItemService;
        $this->translationService = $translationService;
    }

    public function mount($idSurvey, $showDetail = false)
    {
        $this->idSurvey = $idSurvey;
        $this->currentRouteName = Route::currentRouteName();
        $this->showDetail = $showDetail;
    }

    public function render()
    {
        $params['survey'] = $this->surveyService->findOrFail($this->idSurvey);
        $params['question'] = $this->questionService->getById($params['survey']->question?->id);
        $params['responses'] = $params['question']?->serveyQuestionChoice()->get();
        $participation = $this->responseService->countBySurvey($this->idSurvey);
        $stats = [];
        $totalChoosen = 0;
        $totalParticipation = 0;
        $totalChoiceChoosen = $this->responseItemService->countByQuestion(
            $params['survey']->question?->id ?? 0
        );

        if (!is_null($params['responses'])) {
            foreach ($params['responses'] as $response) {
                $choosen = $this->responseItemService->countByQuestionAndChoice(
                    $params['survey']->question->id,
                    $response->id
                );

                $stats[$response->id] = [
                    'title' => $this->translationService->getTranslation($response, 'title', $response->title),
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
        }
        return view('livewire.survey-result', $params)->extends('layouts.master')->section('content');
    }
}
