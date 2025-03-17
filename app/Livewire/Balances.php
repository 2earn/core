<?php

namespace App\Livewire;

use Core\Models\Amount;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Balances extends Component
{

    public $idBalanceOperations;
    public $operation;
    public $io;
    public $source;
    public $mode;
    public $amounts_id;
    public $note;
    public $modify_amount;

    public $search = '';


    public $currentRouteName;

    protected $listeners = [
        'initBOFunction' => 'initBOFunction',
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function initBOFunction($id)
    {

        $balance = BalanceOperation::find($id);
        if (!$balance) {
            throw new \Exception(Lang::get('No Balances'));
        }
        $this->idBalanceOperations = $id;
        $this->operation = $balance->operation;
        $this->io = $balance->io;
        $this->source = $balance->source;
        $this->mode = $balance->mode;
        $this->amounts_id = $balance->amounts_id;
        $this->note = $balance->note;
        $this->modify_amount = $balance->modify_amount;

    }

    public function saveBO()
    {
        try {
            $balance = BalanceOperation::find($this->idBalanceOperations);
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
            $balance->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances', ['locale' => app()->getLocale()])->with('danger', Lang::get('balances update failed'));
        }
        return redirect()->route('balances', ['locale' => app()->getLocale()])->with('success', Lang::get('balances updated successfully'));
    }


    public function render()
    {
        return view('livewire.balances')->extends('layouts.master')->section('content');
    }
}
