<?php

namespace App\Livewire;

use App\Models\PartnerPayment;
use App\Services\PartnerPayment\PartnerPaymentService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class PartnerPaymentDetail extends Component
{
    public $paymentId;
    public $payment;
    public $showValidateModal = false;

    protected $partnerPaymentService;

    protected $listeners = [
        'paymentValidated' => 'refreshPayment'
    ];

    public function boot(PartnerPaymentService $partnerPaymentService)
    {
        $this->partnerPaymentService = $partnerPaymentService;
    }

    public function mount($id)
    {
        $this->paymentId = $id;
        $this->loadPayment();
    }

    public function loadPayment()
    {
        try {
            $this->payment = $this->partnerPaymentService->getById($this->paymentId);
        } catch (\Exception $e) {
            session()->flash('error', Lang::get('Partner payment not found'));
            return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
        }
    }

    public function refreshPayment()
    {
        $this->loadPayment();
        $this->showValidateModal = false;
    }

    public function confirmValidation()
    {
        $this->showValidateModal = true;
    }

    public function validatePayment()
    {
        try {
            $validatorId = auth()->id();
            $this->partnerPaymentService->validatePayment($this->paymentId, $validatorId);

            $this->dispatch('paymentValidated');
            session()->flash('success', Lang::get('Payment validated successfully'));
            $this->loadPayment();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->showValidateModal = false;
    }

    public function deletePayment()
    {
        try {
            $this->partnerPaymentService->delete($this->paymentId);
            session()->flash('success', Lang::get('Partner payment deleted successfully'));
            return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function goToEdit()
    {
        return redirect()->route('partner_payment_manage', [
            'locale' => app()->getLocale(),
            'id' => $this->paymentId
        ]);
    }

    public function goToList()
    {
        return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $params = [
            'payment' => $this->payment,
        ];

        return view('livewire.partner-payment-detail', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}

