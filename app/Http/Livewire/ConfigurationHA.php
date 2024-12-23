<?php

namespace App\Http\Livewire;

use Core\Models\action_historys;
use Core\Models\Amount;
use Core\Models\balanceoperation;
use Core\Models\Setting;
use Core\Services\settingsManager;
use Livewire\Component;

class ConfigurationHA extends Component
{
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
        $lis = [];
        $lists = "";
        $this->list_reponceHA = $list;
        foreach (json_decode($this->list_reponceHA) as $l) {
            $lists = $lists . "," . $l->value;
            $lis[] = $l->value;
        }
        $this->dispatchBrowserEvent('closeModalHA');
    }

    public function render()
    {
        $this->allAmounts = Amount::all();
        return view('livewire.configuration-ha')->extends('layouts.master')->section('content');
    }
}
