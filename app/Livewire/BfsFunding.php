<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class BfsFunding extends Component
{
    public $filter;
    protected $listeners = [
        'redirectPay', 'redirectPay',
    ];

    public function mount($filter,Request $request)
    {
        $this->filter = $filter;

    }
    public function redirectPay($url, $amount)
    {
        switch ($url) {
            case 'paymentpaypal':
                return redirect()->route('payment_paypal', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'paymentcreditcard' :
                return redirect()->route('payment_strip', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
            case 'req_public_user' :
                return redirect()->route('user_request_public', ["locale" => app()->getLocale(), "amount" => $amount]);
                break;
        }
    }

    public function render()
    {
        return view('livewire.bfs-funding');
    }
}
