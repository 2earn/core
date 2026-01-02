<?php

namespace App\Livewire;

use App\Services\Balances\OperationCategoryService;
use Core\Models\Amount;
use Core\Services\BalanceOperationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OperationBalancesCreateUpdate extends Component
{
    public $idOperation;
    public $operation;
    public $ref;
    public $io;
    public $direction;
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

    protected BalanceOperationService $balanceOperationService;
    protected OperationCategoryService $operationCategoryService;

    public function boot(
        BalanceOperationService $balanceOperationService,
        OperationCategoryService $operationCategoryService
    ) {
        $this->balanceOperationService = $balanceOperationService;
        $this->operationCategoryService = $operationCategoryService;
    }

    public function mount(Request $request)
    {
        $this->idOperation = $request->input('idOperation');
        if (!is_null($this->idOperation)) {
            $this->edit($this->idOperation);
        }
        $this->allAmounts = Amount::all();
        $this->allParent = $this->balanceOperationService->getAll();
        $this->allCategory = $this->operationCategoryService->getAll();
        $this->allIO = ['IN', 'OUT', 'IO'];
        $this->allModify = [['name' => 'No', 'value' => 0], ['name' => 'yes', 'value' => 1]];
    }

    public function edit($idOperation)
    {
        $balance = $this->balanceOperationService->findById($idOperation);
        if (!$balance) {
            return;
        }
        $this->operation = $balance->operation;
        $this->direction = $balance->direction;
        $this->ref = $balance->ref;
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
            $balance = $this->balanceOperationService->update($this->idOperation, [
                'operation' => $this->operation,
                'direction' => $this->direction,
                'ref' => $this->ref,
                'source' => $this->source,
                'mode' => $this->mode,
                'amounts_id' => $this->amounts_id,
                'note' => $this->note,
                'modify_amount' => $this->modify_amount,
                'operation_category_id' => $this->operation_category_id,
                'parent_id' => $this->parent_id,
            ]);

            if (!$balance) {
                throw new \Exception(Lang::get('No Balances'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('balances update failed'));
        }
        return redirect()->route('balances_index', ['locale' => app()->getLocale()])->with('success', Lang::get('balances updated successfully'));
    }


    public function render()
    {
        $params = ['balance' => $this->balanceOperationService->findById($this->idOperation)];
        return view('livewire.operation-balances-create-update', $params)->extends('layouts.master')->section('content');
    }
}
