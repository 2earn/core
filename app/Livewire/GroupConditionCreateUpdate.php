<?php

namespace App\Livewire;

use App\Services\Targeting\ConditionService;
use App\Services\Targeting\TargetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class GroupConditionCreateUpdate extends Component
{
    protected ConditionService $conditionService;
    protected TargetService $targetService;

    public $idTarget, $idGroup, $idCondition;
    public $operand, $operator, $value;

    public $update = false;

    public $operands, $operators;

    protected $rules = [
        'operand' => 'required',
        'operator' => 'required',
        'value' => 'required'
    ];

    public function boot(ConditionService $conditionService, TargetService $targetService)
    {
        $this->conditionService = $conditionService;
        $this->targetService = $targetService;
    }

    public function mount($idGroup, Request $request)
    {
        $this->idTarget = Route::current()->parameter('idTarget');
        $this->idGroup = Route::current()->parameter('idGroup');
        $idCondition = $request->input('idCondition');
        if (!is_null($idCondition)) {
            $this->edit($idCondition);
        }
    }

    public function edit($idCondition)
    {
        $question = $this->conditionService->getByIdOrFail($idCondition);
        $this->idCondition = $idCondition;
        $this->operand = $question->operand;
        $this->operator = $question->operator;
        $this->value = $question->value;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Condition operation cancelled'));
    }

    public function updateGroupCondition()
    {
        $this->validate();

        $success = $this->conditionService->update(
            $this->idCondition,
            [
                'operand' => $this->operand,
                'operator' => $this->operator,
                'value' => $this->value,
                'target_group_id' => $this->idGroup,
            ]
        );

        if (!$success) {
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
                ->with('danger', Lang::get('Something goes wrong while updating Condition!!'));
        }

        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
            ->with('success', Lang::get('Condition Updated Successfully'));
    }

    public function store()
    {
        $this->validate();

        $result = $this->conditionService->create([
            'operand' => $this->operand,
            'operator' => $this->operator,
            'value' => $this->value,
            'target_group_id' => $this->idGroup
        ]);

        if (!$result) {
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
                ->with('danger', Lang::get('Something goes wrong while creating Condition!!'));
        }

        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
            ->with('success', Lang::get('Condition Created Successfully'));
    }

    public function render()
    {
        $this->operators = $this->conditionService->getOperators();
        $this->operands = $this->conditionService->getOperands();
        $params = ['target' => $this->targetService->getById($this->idTarget)];
        return view('livewire.condition-create-update', $params)->extends('layouts.master')->section('content');
    }
}
