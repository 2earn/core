<?php

namespace App\Livewire;

use Core\Models\Amount;
use Core\Models\Setting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationSetting extends Component
{
    use WithPagination;

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
    public $perPage = 10;
    public $sortField = 'idSETTINGS';
    public $sortDirection = 'desc';

    protected $listeners = [
        'initSettingFunction' => 'initSettingFunction',
    ];

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function getSettings()
    {
        $query = Setting::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('ParameterName', 'like', '%' . $this->search . '%')
                    ->orWhere('IntegerValue', 'like', '%' . $this->search . '%')
                    ->orWhere('StringValue', 'like', '%' . $this->search . '%')
                    ->orWhere('DecimalValue', 'like', '%' . $this->search . '%')
                    ->orWhere('Unit', 'like', '%' . $this->search . '%')
                    ->orWhere('Description', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function editSetting($id)
    {
        $this->initSettingFunction($id);
        $this->dispatch('openSettingModal');
    }

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
        $settings = $this->getSettings();
        return view('livewire.configuration-setting', [
            'settings' => $settings
        ])->extends('layouts.master')->section('content');
    }

}
