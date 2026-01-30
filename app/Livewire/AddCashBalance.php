<?php

namespace App\Livewire;

use App\Enums\BalanceOperationsEnum;
use App\Models\CashBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddCashBalance extends Component
{
    protected UserService $userService;

    public $search = '';
    public $selectedUserId = null;
    public $selectedUserName = '';
    public $amount = '';
    public $description = '';
    public $showUsersList = false;
    public $users = [];

    protected $rules = [
        'selectedUserId' => 'required|exists:users,idUser',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'selectedUserId.required' => 'Please select a user',
        'selectedUserId.exists' => 'Selected user does not exist',
        'amount.required' => 'Amount is required',
        'amount.numeric' => 'Amount must be a number',
        'amount.min' => 'Amount must be greater than 0',
    ];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updatedSearch()
    {
        $this->showUsersList = false;

        if (empty(trim($this->search))) {
            $this->users = [];
            return;
        }

        // Use UserService to search users
        $users = $this->userService->searchUsers($this->search, 10);
        $this->users = $users->toArray();

        $this->showUsersList = !empty($this->users);
    }

    public function selectUser($userId, $userName)
    {
        $this->selectedUserId = $userId;
        $this->selectedUserName = $userName;
        $this->search = $userName;
        $this->showUsersList = false;
        $this->users = [];
    }

    public function clearSelection()
    {
        $this->selectedUserId = null;
        $this->selectedUserName = '';
        $this->search = '';
        $this->users = [];
        $this->showUsersList = false;
    }

    public function addCash()
    {
        $this->validate();

        try {
            $idUser = $this->selectedUserId;
            $value = floatval($this->amount);
            $balances = new Balances();

            // Get current user balance
            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($idUser);

            if (!$userCurrentBalancehorisontal) {
                session()->flash('error', __('User balance record not found'));
                return;
            }

            // Add cash balance
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::OLD_ID_63->value,
                'operator_id' => Auth::id() ?? $idUser,
                'beneficiary_id' => $idUser,
                'reference' => $balances->getReference(BalanceOperationsEnum::OLD_ID_63->value),
                'description' => !empty($this->description)
                    ? $this->description
                    : "Cash balance added by admin",
                'value' => $value,
                'current_balance' => $userCurrentBalancehorisontal->cash_balance + $value
            ], null, null, null, null, null);

            session()->flash('success', __('Cash balance added successfully for user') . ': ' . $this->selectedUserName);

            // Reset form
            $this->reset(['amount', 'description']);

            Log::info('Cash balance added', [
                'beneficiary_id' => $idUser,
                'operator_id' => Auth::id(),
                'amount' => $value
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding cash balance: ' . $e->getMessage());
            session()->flash('error', __('Failed to add cash balance. Please try again.'));
        }
    }

    public function render()
    {
        return view('livewire.add-cash-balance')->extends('layouts.master')->section('content');
    }
}

