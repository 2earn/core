<?php
namespace App\Livewire;

use App\Services\Balances\BalancesFacade;
use Carbon\Carbon;
use Illuminate\Http\Request as Req;
use Paytabscom\Laravel_paytabs\Facades\paypage;

class Pay
{
    public function test(Req $request)
    {
        $url=  route('notification_from_paytabs',app()->getLocale());
        $name = $email = $street1 = $city = $state = $country = $zip = $ip ="";
        $cart_id = auth()->user()->idUser . '-' .Carbon::now()->timestamp. '-' . rand(1, 1000);
        $cart_amount=$request->amount;
        return paypage::sendPaymentCode('all')
            ->sendTransaction('sale','ecom')
            ->sendCart($cart_id, $cart_amount,'E-CASH TOP-UP')
            ->sendLanguage('en')
            ->sendCustomerDetails($name, $email, auth()->user()->fullphone_number, $street1, $city, $state, $country, $zip, $ip)
            ->sendURLs($url, null)
            ->sendHideShipping(true)
            ->create_pay_page();
    }
}
