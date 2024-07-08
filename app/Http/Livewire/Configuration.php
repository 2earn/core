<?php

namespace App\Http\Livewire;

use Core\Models\action_historys;
use Core\Models\Amount;
use Core\Models\balanceoperation;
use Core\Models\NotificationsSettings;
use Core\Models\Setting;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Configuration extends Component
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

    public $idBalanceOperations;
    public $DesignationBO;
    public $IOBO;
    public $idSourceBO;
    public $ModeBO;
    public $idamountsBO;
    public $NoteBO;
    public $MODIFY_AMOUNT;


    public $idamountsAm;
    public $amountsnameAm;
    public $amountswithholding_taxAm;
    public $amountspaymentrequestAm;
    public $amountstransferAm;
    public $amountscashAm;
    public $amountsactiveAm;
    public $amountsshortnameAm;


    public $idHA;
    public $titleHA;
    public $list_reponceHA;
    public $reponceHA;

    public $search = '';

    protected $listeners = [
        'initSettingFunction' => 'initSettingFunction',
        'editBOFunction' => 'editBOFunction',
        'editAmountsFunction' => 'editAmountsFunction',
        'editHAFunction' => 'editHAFunction',
        'saveHA' => 'saveHA'

    ];

    public function render(settingsManager $settingsManager)
    {

        $this->allAmounts = Amount::all();
        return view('livewire.configuration')->extends('layouts.master')->section('content');
    }

    public function editHAFunction($id)
    {
        $action = action_historys::find($id);
        if (!$action) return;
        $this->idHA = $action->id;
        $this->titleHA = $action->title;
        $this->list_reponceHA = $action->list_reponce;
        $this->reponceHA = $action->reponce;
//        dd($action) ;
    }

    public function editAmountsFunction($id)
    {
        $amount = Amount::find($id);
        if (!$amount) return;

        $this->idamountsAm = $amount->idamounts;
        $this->amountsnameAm = $amount->amountsname;
        $this->amountswithholding_taxAm = $amount->amountswithholding_tax;
        $this->amountspaymentrequestAm = $amount->amountspaymentrequest;
        $this->amountstransferAm = $amount->amountstransfer;
        $this->amountscashAm = $amount->amountscash;
        $this->amountsactiveAm = $amount->amountsactive;
        $this->amountsshortnameAm = $amount->amountsshortname;
    }

    public function editBOFunction($id)
    {
        $balanceOP = balanceoperation::find($id);
        if (!$balanceOP) return;
        $this->idBalanceOperations = $id;
        $this->DesignationBO = $balanceOP->Designation;
        $this->IOBO = $balanceOP->IO;
        $this->idSourceBO = $balanceOP->idSource;
        $this->ModeBO = $balanceOP->Mode;
        $this->idamountsBO = $balanceOP->idamounts;
        $this->NoteBO = $balanceOP->Note;
        $this->MODIFY_AMOUNT = $balanceOP->MODIFY_AMOUNT;
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
        $this->dispatchBrowserEvent('closeModal');
    }

    public function saveBO()
    {
        $balanceOp = balanceoperation::find($this->idBalanceOperations);
        if (!$balanceOp) return;
        $balanceOp->Designation = $this->DesignationBO;
        $balanceOp->IO = $this->IOBO;
        $balanceOp->idSource = $this->idSourceBO;
        $balanceOp->Mode = $this->ModeBO;
        $balanceOp->idamounts = $this->idamountsBO;
        $balanceOp->Note = $this->NoteBO;
        $balanceOp->MODIFY_AMOUNT = $this->MODIFY_AMOUNT;
        $balanceOp->save();
        $this->dispatchBrowserEvent('closeModalOp');
    }

    public function saveAmounts()
    {
        $amount = Amount::find($this->idamountsAm);
        if (!$amount) return;
        $amount->amountsname = $this->amountsnameAm;
        $amount->amountswithholding_tax = $this->amountswithholding_taxAm;
        $amount->amountspaymentrequest = $this->amountspaymentrequestAm;
        $amount->amountstransfer = $this->amountstransferAm;
        $amount->amountscash = $this->amountscashAm;
        $amount->amountsactive = $this->amountsactiveAm;
        $amount->amountsshortname = $this->amountsshortnameAm;
        $amount->save();
        $this->dispatchBrowserEvent('closeModalAmounts');
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
        dd(substr($lists, 1));
    }

}
