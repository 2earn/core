<?php

namespace App\Http\Livewire;

use Core\Models\Amount;
use Core\Models\balanceoperation;
use Livewire\Component;

class ConfigurationBO extends Component
{

    public $idBalanceOperations;
    public $DesignationBO;
    public $IOBO;
    public $idSourceBO;
    public $ModeBO;
    public $idamountsBO;
    public $NoteBO;
    public $MODIFY_AMOUNT;

    public $search = '';

    protected $listeners = [
        'initBOFunction' => 'initBOFunction',
    ];

    public function render()
    {
        $this->allAmounts = Amount::all();
        return view('livewire.configuration-bo')->extends('layouts.master')->section('content');
    }

    public function initBOFunction($id)
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
        $this->dispatchBrowserEvent('closeModalBO');
    }
}
