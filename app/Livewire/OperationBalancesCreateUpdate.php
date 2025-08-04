<?php

namespace App\Livewire;

use App\Models\OperationCategory;
use Core\Models\Amount;
use Core\Models\BalanceOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OperationBalancesCreateUpdate extends Component
{
    public $idOperation;
    public $operation;
    public $io;
    public $source;
    public $mode;
    public $parent_id;
    public $operation_category_id;
    public $amounts_id;
    public $note;
    public $modify_amount;

    public $allAmounts;
    public $allIO;
    public $allParent;
    public $allCategory;
    public $allModify;
    public $update = false;
    protected $rules = ['name' => 'required'];

    public function mount(Request $request)
    {
        $this->idOperation = $request->input('idOperation');
        if (!is_null($this->idOperation)) {
            $this->edit($this->idOperation);
        }
        $this->allAmounts = Amount::all();
        $this->allParent = BalanceOperation::all();
        $this->allCategory = OperationCategory::all();
        $this->allIO = ['I', 'O', 'IO'];
        $this->allModify = [['name' => 'No', 'value' => 0], ['name' => 'yes', 'value' => 1]];
    }

    public function edit($idOperation)
    {
        $balance = BalanceOperation::find($idOperation);
        $this->operation = $balance->operation;
        $this->io = $balance->io;
        $this->source = $balance->source;
        $this->mode = $balance->mode;
        $this->amounts_id = $balance->amounts_id;
        $this->note = $balance->note;
        $this->modify_amount = $balance->modify_amount;
        $this->parent_id = $balance->parent_id;
        $this->operation_category_id = $balance->operation_category_id;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('balances_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Operation operation cancelled'));
    }

    public function saveBO()
    {
        try {
            $balance = BalanceOperation::find($this->idOperation);
            if (!$balance) {
                throw new \Exception(Lang::get('No Balances'));
            }
            $balance->operation = $this->operation;
            $balance->io = $this->io;
            $balance->source = $this->source;
            $balance->mode = $this->mode;
            $balance->amounts_id = $this->amounts_id;
            $balance->note = $this->note;
            $balance->modify_amount = $this->modify_amount;
            $balance->operation_category_id = $this->operation_category_id;
            $balance->parent_id = $this->parent_id;
            $balance->save();
            $balance = BalanceOperation::find($this->idOperation);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('balances update failed'));
        }
        return redirect()->route('balances_index', ['locale' => app()->getLocale()])->with('success', Lang::get('balances updated successfully'));
    }


    public function render()
    {
        $params = ['balance' => BalanceOperation::find($this->idOperation)];
        return view('livewire.operation-balances-create-update', $params)->extends('layouts.master')->section('content');
    }
}
