<?php

namespace App\Livewire;

use App\Models\Condition;
use App\Services\Targeting\ConditionService;
use App\Services\Targeting\TargetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ConditionCreateUpdate extends Component
{
    protected ConditionService $conditionService;
    protected TargetService $targetService;

    public $idTarget, $idGroup, $idCondition;
    public $operand, $operator, $value;
    public $operands, $operators;

    public $update = false;


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

    public function mount(Request $request)
    {
        $this->idTarget = Route::current()->parameter('idTarget');
        $this->idGroup = $request->input('idGroup');
        $this->idCondition = $request->input('idCondition');
        if (!is_null($this->idCondition)) {
            $this->edit($this->idCondition);
        } else {
            $this->init();

        }
    }

    public function init()
    {
        $this->operand = Condition::$operators[1]['value'];
        $this->operator = Condition::$simpleOperands[1];
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
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('warning', Lang::get('Condition operation cancelled'));
    }

    public function updateCondition()
    {
        $this->validate();

        $success = $this->conditionService->update(
            $this->idCondition,
            [
                'operand' => $this->operand,
                'operator' => $this->operator,
                'value' => $this->value
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
        $condition = ['operand' => $this->operand, 'operator' => $this->operator, 'value' => $this->value];

        if (!is_null($this->idGroup)) {
            $condition['group_id'] = $this->idGroup;
        } else {
            $condition['target_id'] = $this->idTarget;
        }

        $result = $this->conditionService->create($condition);

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
