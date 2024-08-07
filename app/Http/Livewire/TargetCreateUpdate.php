<?php

namespace App\Http\Livewire;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class TargetCreateUpdate extends Component
{
    public $idTarget;
    public $name;
    public $description;
    public $update = false;

    protected $rules = [
        'name' => 'required',
        'description' => 'required'
    ];

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
    }

    public function mount(Request $request)
    {
        $idTarget = $request->input('idTarget');
        if (!is_null($idTarget)) {
            $this->edit($idTarget);
        }
    }

    public function cancel()
    {
        return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Target Operation cancelled!!'));
    }

    public function edit($idTarget)
    {
        $target = Target::findOrFail($idTarget);
        $this->name = $target->name;
        $this->description = $target->description;
        $this->idTarget = $idTarget;
        $this->update = true;
    }

    public function update()
    {
        $this->validate();
        try {
            $target = Target::where('id', $this->idTarget)->update(
                [
                    'name' => $this->name,
                    'description' => $this->description
                ]
            );
            return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Target Updated Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Target!!') . ' : ' . $exception->getMessage());
        }
    }

    public function store()
    {
        $this->validate();
        try {
            $target = Target::create(
                [
                    'name' => $this->name,
                    'description' => $this->description,
                ]
            );
            return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Target Created Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Target!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }

    public function render()
    {
        $params = [];
        if (!is_null($this->idTarget) && !empty($this->idTarget)) {
            $params ['target'] = Target::findOrFail($this->idTarget);
        }
        return view('livewire.target-create-update', $params)->extends('layouts.master')->section('content');
    }
}
