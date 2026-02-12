<?php

namespace App\Livewire;

use App\Enums\TargetType;
use App\Models\Target;
use App\Models\TranslaleModel;
use App\Services\Survey\SurveyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SurveyCreateUpdate extends Component
{
    const DATE_FORMAT = "Y-m-d";

    public
        $idSurvey,
        $name,
        $status;

    public $enabled = false;
    public $published = false;
    public $updatable = false;
    public $showResultsAsNumber = false;
    public $showResultsAsPercentage = false;

    public
        $commentable,
        $likable,
        $showResult,
        $show,
        $showAttchivementChrono,
        $showAfterArchiving,
        $showAttchivementGool;

    public
        $startDate,
        $endDate,
        $goals,
        $description,
        $disabledResult,
        $disabledComment,
        $disabledLike;

    public $idTarget = null;
    public $target = null;
    public $targets = null;
    public $update = false;

    protected $listeners = [
        'deleteSurvey' => 'destroy'
    ];

    protected $rules = ['name' => 'required', 'description' => 'required'];

    public $targetTypes;

    protected SurveyService $surveyService;

    public function boot(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function mount(Request $request)
    {
        $idSurvey = $request->input('idSurvey');
        $this->idTarget = $request->input('idTarget');
        if (!is_null($idSurvey)) {
            $this->edit($idSurvey);
        } else {
            $this->resetFields();
        }
    }

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
        $this->commentable = TargetType::ALL->value;
        $this->likable = TargetType::ALL->value;
        $this->showResult = TargetType::ALL->value;
        $this->show = TargetType::ALL->value;
        $this->showAttchivementChrono = TargetType::ALL->value;
        $this->showAfterArchiving = TargetType::ALL->value;
        $this->showAttchivementGool = TargetType::ALL->value;
        if (!is_null($this->idTarget)) {
            $this->target = $this->idTarget;
        } else {
            $targetSelected = Target::all()->first();
            if (!is_null($targetSelected)) {
                $this->target = $targetSelected->id;
            }
        }
    }

    public function validateDisabled()
    {
        if ($this->showResult != TargetType::ALL->value && empty($this->disabledResult)) {
            throw new \Exception(Lang::get('Missed disabled result explanation'));
        }
        if ($this->commentable != TargetType::ALL->value && empty($this->disabledComment)) {
            throw new \Exception(Lang::get('Missed disabled comment explanation'));
        }
        if ($this->likable != TargetType::ALL->value && empty($this->disabledLike)) {
            throw new \Exception(Lang::get('Missed disabled like explanation'));
        }
    }

    public function store()
    {

        $this->validate();
        try {
            $this->validateDisabled();
            $survey = $this->surveyService->create([
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled ? 1 : 0,
                'published' => $this->published ? 1 : 0,
                'updatable' => $this->updatable ? 1 : 0,
                'show_results_as_number' => $this->showResultsAsNumber ? 1 : 0,
                'show_results_as_percentage' => $this->showResultsAsPercentage ? 1 : 0,
                'show' => $this->show,
                'showResult' => $this->showResult,
                'commentable' => $this->commentable,
                'likable' => $this->likable,
                'showAttchivementChrono' => $this->showAttchivementChrono,
                'showAfterArchiving' => $this->showAfterArchiving,
                'showAttchivementGool' => $this->showAttchivementGool,
                'startDate' => getValidCurrentDateTime($this->startDate),
                'endDate' => getValidCurrentDateTime($this->endDate),
                'disabledResult' => $this->disabledResult,
                'disabledComment' => $this->disabledComment,
                'disabledLike' => $this->disabledLike,
                'goals' => $this->goals,
            ]);


            if (!is_null($this->target)) {
                $this->surveyService->attachTargets($survey, [$this->target]);
            }

            createTranslaleModel($survey, 'name', $this->name);
            createTranslaleModel($survey, 'description', $this->description);
            createTranslaleModel($survey, 'disabledResult', $this->disabledResult);
            createTranslaleModel($survey, 'disabledComment', $this->disabledComment);
            createTranslaleModel($survey, 'disabledLike', $this->disabledLike);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_create_update', app()->getLocale())->with('danger', Lang::get('Something goes wrong while creating Survey') . ' | ' . $exception->getMessage());
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $survey->id])->with('success', Lang::get('Survey Created Successfully'));
    }

    public function edit($id)
    {
        $survey = $this->surveyService->findOrFail($id);
        $this->name = $survey->name;
        $this->description = $survey->description;
        $this->idSurvey = $survey->id;
        $this->enabled = $survey->enabled == 1 ? true : false;
        $this->published = $survey->published == 1 ? true : false;
        $this->updatable = $survey->updatable == 1 ? true : false;
        $this->showResultsAsNumber = $survey->show_results_as_number == 1 ? true : false;
        $this->showResultsAsPercentage = $survey->show_results_as_percentage == 1 ? true : false;
        $this->commentable = $survey->commentable;
        $this->likable = $survey->likable;
        $this->show = $survey->show;
        $this->showResult = $survey->showResult;
        $this->showAttchivementChrono = $survey->showAttchivementChrono;
        $this->showAfterArchiving = $survey->showAfterArchiving;
        $this->showAttchivementGool = $survey->showAttchivementGool;
        $this->startDate = !is_null($survey->startDate) ? \Carbon\Carbon::parse($survey->startDate)->format('Y-m-d\TH:i') : null;
        $this->endDate = !is_null($survey->endDate) ? \Carbon\Carbon::parse($survey->endDate)->format('Y-m-d\TH:i') : null;
        $this->goals = $survey->goals;
        $this->disabledResult = $survey->disabledResult;
        $this->disabledComment = $survey->disabledComment;
        $this->disabledLike = $survey->disabledLike;
        if ($survey->targets->isNotEmpty()) {
            $this->target = $survey->targets->first()->id;
        }

        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('surveys_index', app()->getLocale())->with('warning', Lang::get('Survey operation canceled'));
    }

    public function updateSurvey()
    {
        $this->validate();
        try {
            $this->validateDisabled();

            $paramsToUpdate = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled ? 1 : 0,
                'published' => $this->published ? 1 : 0,
                'updatable' => $this->updatable ? 1 : 0,
                'show_results_as_number' => $this->showResultsAsNumber ? 1 : 0,
                'show_results_as_percentage' => $this->showResultsAsPercentage ? 1 : 0,
                'show' => $this->show,
                'showResult' => $this->showResult,
                'commentable' => $this->commentable,
                'likable' => $this->likable,
                'showAttchivementChrono' => $this->showAttchivementChrono,
                'showAfterArchiving' => $this->showAfterArchiving,
                'showAttchivementGool' => $this->showAttchivementGool,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'disabledResult' => $this->disabledResult,
                'disabledComment' => $this->disabledComment,
                'disabledLike' => $this->disabledLike,
                'goals' => $this->goals,
            ];
            if (!is_null($this->startDate)) {
                $paramsToUpdate['startDate'] = getValidCurrentDateTime($this->startDate);
            }
            if (!is_null($this->endDate)) {
                $paramsToUpdate['endDate'] = getValidCurrentDateTime($this->endDate);
            }
            $this->surveyService->updateById($this->idSurvey, $paramsToUpdate);

            if (!is_null($this->target)) {
                $survey = $this->surveyService->getById($this->idSurvey);
                $this->surveyService->attachTargets($survey, [$this->target]);
            }


            $translations = ['name', 'description', 'disabledResult', 'disabledComment', 'disabledLike'];
            foreach ($translations as $translation) {
                $translationModel = TranslaleModel::where('name', TranslaleModel::getTranslateName($survey, $translation))->first();
                if (!is_null($translationModel)) {
                    $translationModel->update(
                        [
                            'value' => $this->{$translation} . ' AR',
                            'valueFr' => $this->{$translation} . ' FR',
                            'valueEn' => $this->{$translation} . ' EN'
                        ]);
                }
            }


        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_create_update', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Survey') . ' | ' . $exception->getMessage());
        }
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Updated Successfully'));

    }

    public function render()
    {
        $this->targets = Target::all();
        $this->targetTypes = TargetType::cases();
        if (is_null($this->target) && $this->targets->isNotEmpty()) {
            $this->target = $this->targets[0]->id;
        }
        return view('livewire.survey-create-update')->extends('layouts.master')->section('content');
    }
}
