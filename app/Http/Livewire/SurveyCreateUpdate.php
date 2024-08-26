<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\Target;
use Core\Enum\TargetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
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
            $survey = Survey::create([
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'published' => $this->published,
                'updatable' => $this->updatable,
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
            ]);
            if (!is_null($this->idTarget)) {
                $survey->targets()->attach([$this->idTarget]);
            }
            if (!is_null($this->target)) {
                $survey->targets()->attach([$this->target]);
            }
        } catch (\Exception $exception) {
            return redirect()->route('survey_create_update', app()->getLocale())->with('danger', Lang::get('Something goes wrong while creating Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $survey->id])->with('success', Lang::get('Survey Created Successfully!!'));
    }

    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        $this->name = $survey->name;
        $this->description = $survey->description;
        $this->idSurvey = $survey->id;
        $this->enabled = $survey->enabled;
        $this->updatable = $survey->updatable;
        $this->commentable = $survey->commentable;
        $this->likable = $survey->likable;
        $this->show = $survey->show;
        $this->showResult = $survey->showResult;
        $this->showAttchivementChrono = $survey->showAttchivementChrono;
        $this->showAfterArchiving = $survey->showAfterArchiving;
        $this->showAttchivementGool = $survey->showAttchivementGool;
        $this->startDate = !is_null($survey->startDate) ? date_format(new \DateTime($survey->startDate), self::DATE_FORMAT) : null;
        $this->endDate = !is_null($survey->endDate) ? date_format(new \DateTime($survey->endDate), self::DATE_FORMAT) : null;
        $this->goals = $survey->goals;
        $this->disabledResult = $survey->disabledResult;
        $this->disabledComment = $survey->disabledComment;
        $this->disabledLike = $survey->disabledLike;
        if (!empty($survey->target)) {
            $this->target = $survey->target->first();
        }
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('surveys_index', app()->getLocale())->with('warning', Lang::get('Survey operation cancelled!!'));
    }

    public function update()
    {
        $this->validate();
        try {
            $this->validateDisabled();

            $paramsToUpdate = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'published' => $this->published,
                'updatable' => $this->updatable,
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
                $paramsToUpdate['startDate'] = $this->startDate;
            }
            if (!is_null($this->endDate)) {
                $paramsToUpdate['endDate'] = $this->endDate;
            }
            Survey::where('id', $this->idSurvey)
                ->update($paramsToUpdate);
            if (!is_null($this->target)) {
                $survey = Survey::find($this->idSurvey);
                $survey->targets()->detach();
                $survey->targets()->attach([$this->target]);
            }
        } catch (\Exception $exception) {
            return redirect()->route('survey_create_update', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Updated Successfully!!'));

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
