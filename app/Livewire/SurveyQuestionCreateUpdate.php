<?php

namespace App\Livewire;

use App\Services\SurveyQuestionService;
use App\Services\SurveyService;
use App\Services\TranslaleModelService;
use Core\Enum\Selection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SurveyQuestionCreateUpdate extends Component
{
    public
        $idQuestion,
        $content,
        $selection = 2,
        $maxResponse = 1;

    public $idSurvey;

    public $update = false;

    public $selections = [
        ['name' => "Multiple", 'value' => 2],
        ['name' => "Unique", 'value' => 1]
    ];
    protected $rules = [
        'content' => 'required',
        'selection' => 'required'
    ];

    protected SurveyQuestionService $questionService;
    protected TranslaleModelService $translationService;
    protected SurveyService $surveyService;

    public function boot(
        SurveyQuestionService $questionService,
        TranslaleModelService $translationService,
        SurveyService $surveyService
    ) {
        $this->questionService = $questionService;
        $this->translationService = $translationService;
        $this->surveyService = $surveyService;
    }

    public function mount(Request $request)
    {
        $idQuestion = $request->input('IdQuestion');
        if (!is_null($idQuestion)) {
            $this->edit($idQuestion);
        }
    }


    public function resetFields()
    {
        $this->content = '';
        $this->selection = '';
    }


    public function cancel()
    {
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Question operation cancelled'));
    }

    public function edit($idQuestion)
    {
        $question = $this->questionService->findOrFail($idQuestion);
        $this->idQuestion = $idQuestion;
        $this->content = $question->content;
        $this->selection = $question->selection;
        $this->maxResponse = $question->maxResponse;
        $this->idSurvey = $question->survey_id;
        $this->update = true;
    }

    public function updateSurveyQuestion()
    {
        $this->validate();
        try {
            $this->validateMultiselection();
            $old = $this->questionService->getById($this->idQuestion);

            $this->questionService->update($this->idQuestion, [
                'content' => $this->content,
                'selection' => $this->selection,
                'maxResponse' => $this->maxResponse != "" ? $this->maxResponse : 0,
            ]);

            $new = $this->questionService->getById($this->idQuestion);

            $question = $this->questionService->getById($this->idQuestion);
            $translationName = $this->translationService->getTranslateName($question, 'content');
            $translationModel = $this->translationService->getByName($translationName);

            if ($new->content != $old->content && !is_null($translationModel)) {
                $this->translationService->updateTranslation($translationModel, $this->content);
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Question'));
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Question Updated Successfully'));

    }

    public function validateMultiselection()
    {
        if ($this->selection == Selection::MULTIPLE->value && ($this->maxResponse == "" or $this->maxResponse == 0)) {
            throw new \Exception(Lang::get('Max response cannot be null when using multiple selections'));
        }
    }

    public function store()
    {
        $this->validate();
        try {
            $this->validateMultiselection();
            $surveyQuestion = $this->questionService->create([
                'content' => $this->content,
                'selection' => $this->selection,
                'maxResponse' => $this->maxResponse != "" ? $this->maxResponse : 0,
                'survey_id' => $this->idSurvey,
            ]);
            createTranslaleModel($surveyQuestion, 'content', $this->content);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while creating Survey'));
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Created Successfully'));
    }

    public function render()
    {
        $params['survey'] = $this->surveyService->findOrFail($this->idSurvey);
        return view('livewire.survey-question-create-update', $params)->extends('layouts.master')->section('content');
    }
}
