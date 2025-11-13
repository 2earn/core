<?php

namespace App\Livewire;

use Core\Models\Setting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ConfigurationSettingEdit extends Component
{
    public int $settingId;
    public string $parameterName;
    public $IntegerValue;
    public $StringValue;
    public $DecimalValue;
    public $Unit;
    public bool $Automatically_calculated;
    public string $Description;

    protected $rules = [
        'Unit' => 'nullable|max:5',
        'Description' => 'nullable|max:250',
        'IntegerValue' => 'nullable|integer',
        'StringValue' => 'nullable|string',
        'DecimalValue' => 'nullable|numeric',
    ];

    public function mount($id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            session()->flash('danger', trans('Setting not found'));
            return redirect()->route('configuration_setting', app()->getLocale());
        }

        $this->settingId = $id;
        $this->parameterName = $setting->ParameterName;
        $this->IntegerValue = $setting->IntegerValue;
        $this->StringValue = $setting->StringValue;
        $this->DecimalValue = $setting->DecimalValue;
        $this->Unit = $setting->Unit;
        $this->Automatically_calculated = $setting->Automatically_calculated ? $setting->Automatically_calculated : 0;
        $this->Description = $setting->Description ? $setting->Description : "";
    }

    public function saveSetting()
    {
        $this->validate();

        try {
            $setting = Setting::find($this->settingId);

            if (!$setting) {
                session()->flash('danger', trans('Setting not found'));
                return redirect()->route('configuration_setting', app()->getLocale());
            }

            $setting->ParameterName = $this->parameterName;
            $setting->IntegerValue = $this->IntegerValue;
            $setting->StringValue = $this->StringValue;
            $setting->DecimalValue = $this->DecimalValue;
            $setting->Unit = $this->Unit;
            $setting->Automatically_calculated = $this->Automatically_calculated;
            $setting->Description = $this->Description;
            $setting->save();

            session()->flash('success', trans('Setting param updated successfully'));
            return redirect()->route('configuration_setting', app()->getLocale());

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', trans('Setting param updating failed'));

        }
        return redirect()->route('configuration_setting', app()->getLocale());
    }

    public function cancel()
    {
        return redirect()->route('configuration_setting', app()->getLocale());
    }

    public function render()
    {
        return view('livewire.configuration-setting-edit')
            ->extends('layouts.master')
            ->section('content');
    }
}
