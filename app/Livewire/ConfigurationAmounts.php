<?php

namespace App\Livewire;

use Core\Models\Amount;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationAmounts extends Component
{
    use WithPagination;

    public $idamountsAm;
    public $amountsnameAm;
    public $amountswithholding_taxAm;
    public $amountspaymentrequestAm;
    public $amountstransferAm;
    public $amountscashAm;
    public $amountsactiveAm;
    public $amountsshortnameAm;

    public $search = '';

    protected $listeners = [
        'initAmountsFunction' => 'initAmountsFunction',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editAmounts($id)
    {
        $this->initAmountsFunction($id);
    }


    public function initAmountsFunction($id)
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


    public function saveAmounts()
    {
        try {
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
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('configuration_amounts', app()->getLocale())->with('danger', trans('Setting param updating failed'));
        }
        return redirect()->route('configuration_amounts', app()->getLocale())->with('success', trans('Setting param updated successfully'));
    }


    public function render(settingsManager $settingsManager)
    {
        $amounts = Amount::query()
            ->when($this->search, function ($query) {
                $query->where('amountsname', 'like', '%' . $this->search . '%')
                      ->orWhere('amountsshortname', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.configuration-amounts', [
            'amounts' => $amounts
        ])->extends('layouts.master')->section('content');
    }


}
