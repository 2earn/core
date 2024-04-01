<?php
namespace App\Http\Livewire;

use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Paytabs1;

use Paytabscom\Laravel_paytabs\Facades\paypage;


class pay
{
    public function test(Req $request)
    {
        $url=  route('paytabs_notification');

        $name='';
        $email='';
        $street1='';
        $city='';
        $state='';
        $country='';
        $zip='';
        $ip='';


        $cart_id = auth()->user()->idUser.'-'.DB::table('user_balances')->where('idBalancesOperation', 44)->where('idUser', auth()->user()->idUser)->count()+1;
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



