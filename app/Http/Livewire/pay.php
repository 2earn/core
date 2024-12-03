<?php
namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Paytabs1;

use Paytabscom\Laravel_paytabs\Facades\paypage;


class pay
{
  //??
    //// --> TO CHECK
    public function test(Req $request)
    {
        $url=  route('notification_from_paytabs',app()->getLocale());

        $name='';
        $email='';
        $street1='';
        $city='';
        $state='';
        $country='';
        $zip='';
        $ip='';


        // CONVERTED IN BALANCES
        $cart_id = auth()->user()->idUser.'-'.BalancesFacade::getBalanceCompter();
        $cart_amount=$request->amount;

        $pay= paypage::sendPaymentCode('all')
            ->sendTransaction('sale','ecom')
            ->sendCart($cart_id, $cart_amount,'E-CASH TOP-UP')
            ->sendLanguage('en')
            ->sendCustomerDetails($name, $email, auth()->user()->fullphone_number, $street1, $city, $state, $country, $zip, $ip)
            ->sendURLs($url, null)
            ->sendHideShipping(true)

            ->create_pay_page();


        return $pay;
    }

}



