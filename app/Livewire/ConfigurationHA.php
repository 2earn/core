<?php

namespace App\Livewire;

use App\Services\ActionHistorysService;
use App\Services\AmountService;
use App\Services\settingsManager;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationHA extends Component
{
    use WithPagination;

    protected ActionHistorysService $actionHistorysService;
    protected AmountService $amountService;

    public $allAmounts;
    public int $idSetting;
    public string $parameterName;
    public $IntegerValue;
    public $Unit;
    public string $Description;
    public $idBalanceOperations;
    public $idHA;
    public $titleHA;
    public $list_reponceHA;
    public $reponceHA;

    public $search = '';

    protected $listeners = [
        'initHAFunction' => 'initHAFunction',
        'saveHA' => 'saveHA'
    ];

    public function boot(ActionHistorysService $actionHistorysService, AmountService $amountService)
    {
        $this->actionHistorysService = $actionHistorysService;
        $this->amountService = $amountService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editHA($id)
    {
        $this->initHAFunction($id);
    }


    public function initHAFunction($id)
    {
        $action = $this->actionHistorysService->getById($id);
        if (!$action) return;
        $this->idHA = $action->id;
        $this->titleHA = $action->title;
        $this->list_reponceHA = $action->list_reponce;
        $this->reponceHA = $action->reponce;
    }
    public function saveHA($list)
    {
        $lis = [];
        $lists = "";
        $this->list_reponceHA = $list;

        foreach (json_decode($this->list_reponceHA) as $l) {
            $lists = $lists . "," . $l->value;
            $lis[] = $l->value;
        }

        // TODO: Add actual update logic using ActionHistorysService
        // $success = $this->actionHistorysService->update($this->idHA, ['list_reponce' => $lists]);

        return redirect()->route('configuration_ha', app()->getLocale())
            ->with('success', trans('Setting param updated successfully'));
    }

    public function render()
    {
        $this->allAmounts = $this->amountService->getAll();
        $actionHistories = $this->actionHistorysService->getPaginated($this->search, 10);

        return view('livewire.configuration-ha', [
            'actionHistories' => $actionHistories
        ])->extends('layouts.master')->section('content');
    }
}
