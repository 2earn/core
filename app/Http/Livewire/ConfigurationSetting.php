<?php

namespace App\Http\Livewire;

use Core\Models\Amount;
use Core\Models\Setting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ConfigurationSetting extends Component
{

    public $allAmounts;
    public int $idSetting;
    public string $parameterName;
    public $IntegerValue;
    public $StringValue;
    public $DecimalValue;
    public $Unit;
    public bool $Automatically_calculated;
    public string $Description;

    public $search = '';

    protected $listeners = [
        'initSettingFunction' => 'initSettingFunction',
    ];

    public function initSettingFunction($id)
    {
        $setting = Setting::find($id);
        if (!$setting) return;
        $this->idSetting = $id;
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
        try {
            $setting = Setting::find($this->idSetting);
            if (!$setting) return;
            $setting->ParameterName = $this->parameterName;
            $setting->IntegerValue = $this->IntegerValue;
            $setting->StringValue = $this->StringValue;
            $setting->DecimalValue = $this->DecimalValue;
            $setting->Unit = $this->Unit;
            $setting->Automatically_calculated = $this->Automatically_calculated;
            $setting->Description = $this->Description;
            $setting->save();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('configuration_setting', app()->getLocale())->with('danger', trans('Setting param updating failed'));
        }
        return redirect()->route('configuration_setting', app()->getLocale())->with('success', trans('Setting param updated successfully'));

    }

    public function render()
    {
        $this->allAmounts = Amount::all();
        return view('livewire.configuration-setting')->extends('layouts.master')->section('content');
    }

}
