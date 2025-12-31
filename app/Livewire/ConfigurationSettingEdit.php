<?php

namespace App\Livewire;

use App\Services\Settings\SettingService;
use Livewire\Component;

class ConfigurationSettingEdit extends Component
{
    protected SettingService $settingService;

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

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($id)
    {
        $setting = $this->settingService->getById($id);

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

        $success = $this->settingService->updateSetting(
            $this->settingId,
            [
                'ParameterName' => $this->parameterName,
                'IntegerValue' => $this->IntegerValue,
                'StringValue' => $this->StringValue,
                'DecimalValue' => $this->DecimalValue,
                'Unit' => $this->Unit,
                'Automatically_calculated' => $this->Automatically_calculated,
                'Description' => $this->Description,
            ]
        );

        if (!$success) {
            session()->flash('danger', trans('Setting param updating failed'));
            return redirect()->route('configuration_setting', app()->getLocale());
        }

        session()->flash('success', trans('Setting param updated successfully'));
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
