<?php

namespace App\Http\Livewire;

use Core\Models\Amount;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class Balances extends Component
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


    public function initBOFunction($id)
    {
        try {
            $balance = BalanceOperation::find($id);

            if (!$balance) {
                throw new \Exception(Lang::get('No Balances'));
            }
            $this->idBalanceOperations = $id;
            $this->DesignationBO = $balance->Designation;
            $this->IOBO = $balance->IO;
            $this->idSourceBO = $balance->idSource;
            $this->ModeBO = $balance->Mode;
            $this->idamountsBO = $balance->idamounts;
            $this->NoteBO = $balance->Note;
            $this->MODIFY_AMOUNT = $balance->MODIFY_AMOUNT;
        } catch (\Exception $exception) {
            return redirect()->route('balances', ['locale' => app()->getLocale()])->with('error', Lang::get('balances init failed'));

        }
    }

    public function saveBO()
    {
        try {
            $balance = BalanceOperation::find($this->idBalanceOperations);
            if (!$balance) {
                throw new \Exception(Lang::get('No Balances'));
            }
            $balance->Designation = $this->DesignationBO;
            $balance->IO = $this->IOBO;
            $balance->idSource = $this->idSourceBO;
            $balance->Mode = $this->ModeBO;
            $balance->idamounts = $this->idamountsBO;
            $balance->Note = $this->NoteBO;
            $balance->MODIFY_AMOUNT = $this->MODIFY_AMOUNT;
            $balance->save();
        } catch (\Exception $exception) {
            return redirect()->route('balances', ['locale' => app()->getLocale()])->with('error', Lang::get('balances update failed'));

        }
        return redirect()->route('balances', ['locale' => app()->getLocale()])->with('success', Lang::get('balances updated successfully'));
    }


    public function render()
    {
        $this->allAmounts = Amount::all();
        return view('livewire.balances')->extends('layouts.master')->section('content');
    }
}
