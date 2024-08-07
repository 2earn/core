<?php

namespace App\Http\Livewire;

use App\Models\GroupCondition;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class GroupConditionCreateUpdate extends Component
{
    public $idTarget, $idGroup, $idCondition;
    public $operand = "country", $operator = 'eq', $value;

    public $update = false;

    public $operators = [
        ['name' => 'country', 'value' => 'country'],
        ['name' => 'city', 'value' => 'city'],
        ['name' => 'gender', 'value' => 'gender'],
        ['name' => 'birthDate', 'value' => 'birthDate']
    ];
    public $operands = [
        ['name' => 'eq', 'value' => '=='],
        ['name' => 'neq', 'value' => '!=='],
        ['name' => 'gt', 'value' => '<'],
        ['name' => 'lt', 'value' => '>'],
        ['name' => 'egt', 'value' => '<='],
        ['name' => 'elt', 'value' => '>='],
        ['name' => 'In', 'value' => 'In'],
    ];

    protected $rules = [
        'operand' => 'required',
        'operator' => 'required',
        'value' => 'required'
    ];

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
        $question = GroupCondition::findOrFail($idCondition);
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
            GroupCondition::where('id', $this->idCondition)
                ->update([
                    'operand' => $this->operand,
                    'operator' => $this->operator,
                    'value' => $this->value,
                    'group_id' => $this->idGroup,
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
        try {
            $condition = GroupCondition::create([
                'operand' => $this->operand,
                'operator' => $this->operator,
                'value' => $this->value,
                'group_id' => $this->idGroup,
            ]);
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Created Successfully!!') . ' ' . $condition->content);
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
