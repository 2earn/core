<?php

namespace App\Livewire;

use App\Models\PartnerPayment;
use App\Models\User;
use App\Services\PartnerPayment\PartnerPaymentService;
use Core\Models\FinancialRequest;
use Core\Models\Platform;
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
    public $update = false;

    // Search helpers
    public $searchUser = '';
    public $searchPartner = '';
    public $searchDemand = '';

    protected PartnerPaymentService $partnerPaymentService;


    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'payment_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'partner_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $isPlatformPartner = \DB::table('platforms')
                        ->where(function ($query) use ($value) {
                            $query->where('financial_manager_id', $value)
                                ->orWhere('marketing_manager_id', $value)
                                ->orWhere('owner_id', $value);
                        })
                        ->exists();

                    if (!$isPlatformPartner) {
                        $fail('The selected partner must be a platform manager or owner.');
                    }
                },
            ],
        ];
    }

    protected $messages = [
        'amount.required' => 'Amount is required',
        'amount.numeric' => 'Amount must be a number',
        'amount.min' => 'Amount must be greater than or equal to 0',
        'method.required' => 'Payment method is required',
        'user_id.required' => 'User is required',
        'user_id.exists' => 'Selected user does not exist',
        'partner_id.required' => 'Partner is required',
        'partner_id.exists' => 'Selected partner does not exist',
    ];

    public function boot(PartnerPaymentService $partnerPaymentService)
    {
        $this->partnerPaymentService = $partnerPaymentService;
    }

    public function mount(Request $request)
    {
        // Check if user has partner special role
        if (!Platform::havePartnerSpecialRole(auth()->user()->id)) {
            session()->flash('error', Lang::get('You do not have permission to access this page'));
            return redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
        }

        $this->paymentId = $request->input('id');
        $this->payment_date = now()->format('Y-m-d\TH:i');

        if (!is_null($this->paymentId)) {
            $this->edit($this->paymentId);
        } else {
            // Default to current user if creating new payment
            $this->user_id = auth()->id();
        }
    }

    public function edit($paymentId): void
    {
        try {
            $payment = $this->partnerPaymentService->getById($paymentId);

            // Check if payment is validated (cannot edit validated payments)
            if ($payment->isValidated()) {
                session()->flash('error', Lang::get('Cannot edit a validated payment'));
                redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
                return;
            }

            $this->paymentId = $paymentId;
            $this->amount = $payment->amount;
            $this->method = $payment->method;
            $this->payment_date = $payment->payment_date?->format('Y-m-d\TH:i');
            $this->user_id = $payment->user_id;
            $this->partner_id = $payment->partner_id;
            $this->update = true;
        } catch (\Exception $e) {
            session()->flash('error', Lang::get('Partner payment not found'));
            redirect()->route('partner_payment_index', ['locale' => app()->getLocale()]);
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
            ];

            if ($this->update) {
                $this->partnerPaymentService->update($this->paymentId, $data);
                session()->flash('success', Lang::get('Partner payment updated successfully'));
            } else {
                $payment = $this->partnerPaymentService->create($data);
                session()->flash('success', Lang::get('Partner payment created successfully'));
                $this->paymentId = $payment->id;
            }

            redirect()->route('partner_payment_detail', [
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

        // Get unique partner IDs from platforms table
        $partnerIds = \DB::table('platforms')
            ->select('financial_manager_id', 'marketing_manager_id', 'owner_id')
            ->get()
            ->flatMap(function ($platform) {
                return [
                    $platform->financial_manager_id,
                    $platform->marketing_manager_id,
                    $platform->owner_id
                ];
            })
            ->filter()
            ->unique()
            ->values();

        // Search only among platform partners
        return User::whereIn('id', $partnerIds)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchPartner . '%')
                    ->orWhere('email', 'like', '%' . $this->searchPartner . '%')
                    ->orWhere('id', 'like', '%' . $this->searchPartner . '%');
            })
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

