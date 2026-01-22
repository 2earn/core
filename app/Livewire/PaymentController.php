<?php
namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class PaymentController extends Component
{
    public function render()
    {

        return view('livewire.user-balance-c-b')->extends('layouts.master')->section('content');
    }

    public function handlePaymentNotification($requestData)
    {
        dd($requestData);

        return response()->json(['status' => 'success']);
    }
}
