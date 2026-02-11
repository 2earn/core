<?php

namespace App\Livewire;

use App\Services\Survey\SurveyQuestionChoiceService;
use App\Services\Survey\SurveyQuestionService;
use App\Services\Survey\SurveyService;
use App\Services\TranslaleModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyQuestionChoiceCreateUpdate extends Component
{
    public $idQuestion;
    public $title;
    public $idSurvey;
    public $idChoice;
    public $update = false;
    protected $rules = [
        'title' => 'required'
    ];

    protected SurveyQuestionChoiceService $choiceService;
    protected TranslaleModelService $translationService;
    protected SurveyService $surveyService;
    protected SurveyQuestionService $questionService;

    public function boot(
        SurveyQuestionChoiceService $choiceService,
        TranslaleModelService $translationService,
        SurveyService $surveyService,
        SurveyQuestionService $questionService
    ) {
        $this->choiceService = $choiceService;
        $this->translationService = $translationService;
        $this->surveyService = $surveyService;
        $this->questionService = $questionService;
    }

    public function resetFields()
    {
        $this->title = '';
    }

    public function mount(Request $request)
    {
        $this->idSurvey = Route::current()->parameter('idSurvey');
        $this->idQuestion = Route::current()->parameter('idQuestion');
        $idChoice = $request->input('idChoice');
        if (!is_null($idChoice)) {
            $this->edit($idChoice);
        }
    }

    public function cancel()
    {
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Choice operation canceled'));
    }

    public function edit($idChoice)
    {
        $choice = $this->choiceService->findOrFail($idChoice);
        $this->idQuestion = $choice->question_id;
        $this->title = $choice->title;
        $this->idChoice = $idChoice;
        $this->update = true;
    }

    public function updateSurveyQuestionChoice()
    {
        $this->validate();
        try {
            $old = $this->choiceService->getById($this->idChoice);
            $this->choiceService->updateById($this->idChoice, ['title' => $this->title]);
            $new = $this->choiceService->getById($this->idChoice);

            $choice = $this->choiceService->getById($this->idChoice);
            $translationName = $this->translationService->getTranslateName($choice, 'title');
            $translationModel = $this->translationService->getByName($translationName);

            if ($new->title != $old->title && !is_null($translationModel)) {
                $this->translationService->updateTranslation($translationModel, $this->title);
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Choice'));
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Choice Updated Successfully'));
    }

    public function store()
    {
        $this->validate();
        try {
            $surveyQuestionChoice = $this->choiceService->create([
                'title' => $this->title,
                'question_id' => $this->idQuestion
            ]);
            createTranslaleModel($surveyQuestionChoice, 'title', $this->title);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while creating Choice'));
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Choice Created Successfully'));
    }


    public function render()
    {
        $params = [
            'idSurvey' => $this->idSurvey,
            'idQuestion' => $this->idQuestion,
            'question' => $this->questionService->getById($this->idQuestion),
            'survey' => $this->surveyService->getById($this->idSurvey)
        ];
        return view('livewire.survey-question-choice-create-update', $params)->extends('layouts.master')->section('content');
    }
}
