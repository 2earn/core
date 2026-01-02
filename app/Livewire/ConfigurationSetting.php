<?php

namespace App\Livewire;

use App\Services\AmountService;
use App\Services\Settings\SettingService;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationSetting extends Component
{
    use WithPagination;

    protected SettingService $settingService;
    protected AmountService $amountService;

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

    public function boot(SettingService $settingService, AmountService $amountService)
    {
        $this->settingService = $settingService;
        $this->amountService = $amountService;
    }

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
        return $this->settingService->getPaginatedSettings(
            $this->search,
            $this->sortField,
            $this->sortDirection,
            $this->perPage
        );
    }

    public function editSetting($id)
    {
        $this->initSettingFunction($id);
        $this->dispatch('openSettingModal');
    }

    public function initSettingFunction($id)
    {
        $setting = $this->settingService->getById($id);
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
        $success = $this->settingService->updateSetting(
            $this->idSetting,
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
            return redirect()->route('configuration_setting', app()->getLocale())
                ->with('danger', trans('Setting param updating failed'));
        }

        return redirect()->route('configuration_setting', app()->getLocale())
            ->with('success', trans('Setting param updated successfully'));
    }

    public function render()
    {
        $this->allAmounts = $this->amountService->getAll();
        $settings = $this->getSettings();
        return view('livewire.configuration-setting', [
            'settings' => $settings
        ])->extends('layouts.master')->section('content');
    }

}
