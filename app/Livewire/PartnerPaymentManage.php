<?php

namespace App\Livewire;

use App\Models\PartnerPayment;
use App\Models\User;
use App\Services\PartnerPayment\PartnerPaymentService;
use Core\Models\FinancialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PartnerPaymentManage extends Component
{
    public $paymentId;
    public $amount;
    public $method = 'bank_transfer';
    public $payment_date;
    public $user_id;
    public $partner_id;
    public $demand_id;
    public $update = false;

    // Search helpers
    public $searchUser = '';
    public $searchPartner = '';
    public $searchDemand = '';

    protected PartnerPaymentService $partnerPaymentService;

    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'method' => 'required|string|max:50',
        'payment_date' => 'nullable|date',
        'user_id' => 'required|exists:users,id',
        'partner_id' => 'required|exists:users,id',
        'demand_id' => 'nullable|string|max:9',
    ];

    protected $messages = [
        'amount.required' => 'Amount is required',
        'amount.numeric' => 'Amount must be a number',
        'amount.min' => 'Amount must be greater than or equal to 0',
        'method.required' => 'Payment method is required',
        'user_id.required' => 'User is required',
        'user_id.exists' => 'Selected user does not exist',
        'partner_id.required' => 'Partner is required',
        'partner_id.exists' => 'Selected partner does not exist',
        'demand_id.max' => 'Demand ID must not exceed 9 characters',
    ];

    public function boot(PartnerPaymentService $partnerPaymentService)
    {
        $this->partnerPaymentService = $partnerPaymentService;
    }

    public function mount(Request $request)
    {
        $this->paymentId = $request->input('id');
        $this->payment_date = now()->format('Y-m-d\TH:i');

        if (!is_null($this->paymentId)) {
            $this->edit($this->paymentId);
        } else {
            // Default to current user if creating new payment
            $this->user_id = auth()->id();
        }
    }

    public function edit($paymentId)
    {
        try {
            $payment = $this->partnerPaymentService->getById($paymentId);

            // Check if payment is validated (cannot edit validated payments)
            if ($payment->isValidated()) {
                session()->flash('error', Lang::get('Cannot edit a validated payment'));
                return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
            }

            $this->paymentId = $paymentId;
            $this->amount = $payment->amount;
            $this->method = $payment->method;
            $this->payment_date = $payment->payment_date?->format('Y-m-d\TH:i');
            $this->user_id = $payment->user_id;
            $this->partner_id = $payment->partner_id;
            $this->demand_id = $payment->demand_id;
            $this->update = true;
        } catch (\Exception $e) {
            session()->flash('error', Lang::get('Partner payment not found'));
            return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
        }
    }

    public function cancel()
    {
        return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()])
            ->with('warning', Lang::get('Partner payment operation cancelled'));
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'amount' => $this->amount,
                'method' => $this->method,
                'payment_date' => $this->payment_date,
                'user_id' => $this->user_id,
                'partner_id' => $this->partner_id,
                'demand_id' => $this->demand_id,
            ];

            if ($this->update) {
                $this->partnerPaymentService->update($this->paymentId, $data);
                session()->flash('success', Lang::get('Partner payment updated successfully'));
            } else {
                $payment = $this->partnerPaymentService->create($data);
                session()->flash('success', Lang::get('Partner payment created successfully'));
                $this->paymentId = $payment->id;
            }

            return redirect()->route('partner_payment_detail', [
                'locale' => app()->getLocale(),
                'id' => $this->paymentId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save partner payment: ' . $e->getMessage());
            session()->flash('error', Lang::get('Failed to save partner payment: ') . $e->getMessage());
        }
    }

    public function searchUsers()
    {
        if (empty($this->searchUser)) {
            return [];
        }

        return User::where('name', 'like', '%' . $this->searchUser . '%')
            ->orWhere('email', 'like', '%' . $this->searchUser . '%')
            ->orWhere('id', 'like', '%' . $this->searchUser . '%')
            ->limit(10)
            ->get();
    }

    public function searchPartners()
    {
        if (empty($this->searchPartner)) {
            return [];
        }

        return User::where('name', 'like', '%' . $this->searchPartner . '%')
            ->orWhere('email', 'like', '%' . $this->searchPartner . '%')
            ->orWhere('id', 'like', '%' . $this->searchPartner . '%')
            ->limit(10)
            ->get();
    }

    public function searchDemands()
    {
        if (empty($this->searchDemand)) {
            return [];
        }

        return FinancialRequest::where('numeroReq', 'like', '%' . $this->searchDemand . '%')
            ->orWhere('idSender', 'like', '%' . $this->searchDemand . '%')
            ->limit(10)
            ->get();
    }

    public function selectUser($userId)
    {
        $this->user_id = $userId;
        $this->searchUser = '';
    }

    public function selectPartner($partnerId)
    {
        $this->partner_id = $partnerId;
        $this->searchPartner = '';
    }

    public function selectDemand($demandId)
    {
        $this->demand_id = $demandId;
        $this->searchDemand = '';
    }

    public function getSelectedUser()
    {
        if ($this->user_id) {
            return User::find($this->user_id);
        }
        return null;
    }

    public function getSelectedPartner()
    {
        if ($this->partner_id) {
            return User::find($this->partner_id);
        }
        return null;
    }

    public function getSelectedDemand()
    {
        if ($this->demand_id) {
            return FinancialRequest::where('numeroReq', $this->demand_id)->first();
        }
        return null;
    }

    public function render()
    {
        $params = [
            'update' => $this->update,
            'selectedUser' => $this->getSelectedUser(),
            'selectedPartner' => $this->getSelectedPartner(),
            'selectedDemand' => $this->getSelectedDemand(),
            'paymentMethods' => [
                'bank_transfer' => 'Bank Transfer',
                'cash' => 'Cash',
                'check' => 'Check',
                'online_payment' => 'Online Payment',
                'mobile_payment' => 'Mobile Payment',
                'wire_transfer' => 'Wire Transfer',
                'other' => 'Other',
            ],
        ];

        return view('livewire.partner-payment-manage', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}

