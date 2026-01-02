<?php

namespace App\Livewire;

use App\Services\AmountService;
use Core\Services\settingsManager;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationAmounts extends Component
{
    use WithPagination;

    protected AmountService $amountService;

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

    public function boot(AmountService $amountService)
    {
        $this->amountService = $amountService;
    }

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
        $amount = $this->amountService->getById($id);
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
        $success = $this->amountService->update(
            $this->idamountsAm,
            [
                'amountsname' => $this->amountsnameAm,
                'amountswithholding_tax' => $this->amountswithholding_taxAm,
                'amountspaymentrequest' => $this->amountspaymentrequestAm,
                'amountstransfer' => $this->amountstransferAm,
                'amountscash' => $this->amountscashAm,
                'amountsactive' => $this->amountsactiveAm,
                'amountsshortname' => $this->amountsshortnameAm,
            ]
        );

        if (!$success) {
            return redirect()->route('configuration_amounts', app()->getLocale())
                ->with('danger', trans('Setting param updating failed'));
        }

        return redirect()->route('configuration_amounts', app()->getLocale())
            ->with('success', trans('Setting param updated successfully'));
    }


    public function render(settingsManager $settingsManager)
    {
        $amounts = $this->amountService->getPaginated($this->search, 10);

        return view('livewire.configuration-amounts', [
            'amounts' => $amounts
        ])->extends('layouts.master')->section('content');
    }

}
