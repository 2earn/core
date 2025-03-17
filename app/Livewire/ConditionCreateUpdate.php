<?php

namespace App\Livewire;

use App\Models\Condition;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ConditionCreateUpdate extends Component
{
    public $idTarget, $idGroup, $idCondition;
    public $operand, $operator, $value;
    public $operands, $operators;

    public $update = false;


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
        $question = Condition::findOrFail($idCondition);
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

    public function update()
    {
        $this->validate();

        try {
            Condition::where('id', $this->idCondition)
                ->update(['operand' => $this->operand, 'operator' => $this->operator, 'value' => $this->value]);
        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while updating Condition!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Updated Successfully'));

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
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while creating Condition!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Created Successfully'));
    }


    public function render()
    {
        $this->operators = Condition::$operators;
        $this->operands = Condition::operands();
        $params = ['target' => Target::find($this->idTarget)];
        return view('livewire.condition-create-update', $params)->extends('layouts.master')->section('content');
    }
}
