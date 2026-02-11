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
    public function saveHA($list = null)
    {
        // If list is provided as parameter, use it; otherwise use the component property
        if ($list !== null) {
            $this->list_reponceHA = $list;
        }

        if (!$this->list_reponceHA) {
            session()->flash('error', trans('No data to save'));
            return;
        }

        $lis = [];
        $lists = "";

        $decodedList = is_string($this->list_reponceHA)
            ? json_decode($this->list_reponceHA)
            : $this->list_reponceHA;

        if ($decodedList) {
            foreach ($decodedList as $l) {
                $value = is_object($l) && isset($l->value) ? $l->value : $l;
                $lists = $lists . "," . $value;
                $lis[] = $value;
            }
            // Remove leading comma
            $lists = ltrim($lists, ',');
        }

        // Update using ActionHistorysService
        if ($this->idHA) {
            try {
                $success = $this->actionHistorysService->update($this->idHA, ['list_reponce' => $lists]);

                if ($success) {
                    session()->flash('success', trans('Setting param updated successfully'));
                } else {
                    session()->flash('error', trans('Failed to update setting'));
                }
            } catch (\Exception $e) {
                session()->flash('error', trans('Error updating setting: ') . $e->getMessage());
            }
        }

        return redirect()->route('configuration_ha', ['locale' => app()->getLocale()]);
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
