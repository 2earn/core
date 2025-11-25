<?php

namespace App\Livewire;

use Core\Models\action_historys;
use Core\Models\Amount;
use Core\Models\balanceoperation;
use Core\Models\Setting;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationHA extends Component
{
    use WithPagination;
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
        $action = action_historys::find($id);
        if (!$action) return;
        $this->idHA = $action->id;
        $this->titleHA = $action->title;
        $this->list_reponceHA = $action->list_reponce;
        $this->reponceHA = $action->reponce;
    }
    public function saveHA($list)
    {
        try {
            $lis = [];
            $lists = "";
            $this->list_reponceHA = $list;
            foreach (json_decode($this->list_reponceHA) as $l) {
                $lists = $lists . "," . $l->value;
                $lis[] = $l->value;
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('configuration_ha', app()->getLocale())->with('danger', trans('Setting param updating failed'));
        }
        return redirect()->route('configuration_ha', app()->getLocale())->with('success', trans('Setting param updated successfully'));
    }

    public function render()
    {
        $this->allAmounts = Amount::all();

        $actionHistories = action_historys::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.configuration-ha', [
            'actionHistories' => $actionHistories
        ])->extends('layouts.master')->section('content');
    }
}
