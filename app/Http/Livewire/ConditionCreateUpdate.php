<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ConditionCreateUpdate extends Component
{
    public $idTarget, $idGroup, $idCondition;
    public $operand = "country", $operator = 'eq', $value;

    public $update = false;

    public $operators = [
        ['name' => 'name', 'value' => 'u.name'],
        ['name' => 'email', 'value' => 'u.email'],
        ['name' => 'idUpline', 'value' => 'u.idUpline'],
        ['name' => 'idUser', 'value' => 'u.idUser'],
        ['name' => 'mobile', 'value' => 'u.mobile'],
        ['name' => 'fullphone_number', 'value' => 'u.fullphone_number'],
        ['name' => 'status', 'value' => 'u.status']
    ];

    public $operands = [
        ['name' => 'equal', 'value' => '='],
        ['name' => 'not-equal', 'value' => '!='],
        ['name' => 'less-than', 'value' => '<'],
        ['name' => 'more-than', 'value' => '>'],
        ['name' => 'less-than-or-equal', 'value' => '<='],
        ['name' => 'more-than-or-equal', 'value' => '>=']
    ];

    protected $rules = [
        'operand' => 'required',
        'operator' => 'required',
        'value' => 'required'
    ];

    public function mount(Request $request)
    {
        $this->idTarget = Route::current()->parameter('idTarget');
        $this->idGroup = $request->input('idGroup');
        $this->idCondition = $request->input('idCondition');
        if (!is_null($this->idCondition)) {
            $this->edit($this->idCondition);
        }
    }

    public function edit($idCondition)
    {
        $question = Condition::findOrFail($idCondition);
        $this->idCondition = $idCondition;
        $this->operand = $question->operand;
        $this->operator = $question->operator;
        $this->value = $question->value;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Condition Operation cancelled!!'));
    }

    public function update()
    {
        $this->validate();

        try {
            Condition::where('id', $this->idCondition)
                ->update([
                    'operand' => $this->operand,
                    'operator' => $this->operator,
                    'value' => $this->value,
                ]);
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Updated Successfully!!'));
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while updating Condition!!') . ' : ' . $exception->getMessage());
        }
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
        try {
            Condition::create($condition);
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Created Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while creating Condition!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }


    public function render()
    {
        $params = ['target' => Target::find($this->idTarget)];
        return view('livewire.condition-create-update', $params)->extends('layouts.master')->section('content');
    }
}
