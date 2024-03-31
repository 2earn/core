<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Paytabs1;


class pay extends Component
{

    public function render()
    {

        require_once 'Paytabs1.php';
        $plugin = new Paytabs1();
        $request_url = 'payment/request';
        $data =   [
            "tran_type"=> "sale",
            "tran_class"=> "ecom",
            "cart_id"=> "1212",
            "cart_currency"=> "SAR",
            "cart_amount"=> 12.3,
            "cart_description"=> "Description of the items/services",
            "paypage_lang"=> "en",
            "customer_details"=> [
                "name"=> "first last",
                "email"=> "email@domain.com",
                "phone"=> "0522222222",
                "street1"=> "address street",
                "city"=> "dubai",
                "state"=> "du",
                "country"=> "AE",
                "zip"=> "12345"
            ],
            "shipping_details"=> [
                "name"=> "name1 last1",
                "email"=> "email1@domain.com",
                "phone"=> "971555555555",
                "street1"=> "street2",
                "city"=> "dubai",
                "state"=> "dubai",
                "country"=> "AE",
                "zip"=> "54321"
            ],
            "callback"=> "http://127.0.0.1:8000/en",
            "return"=> "http://127.0.0.1:8000/en/contacts",
            "framed"=> true
        ]
        ;
        $page = $plugin->send_api_request($request_url, $data);
        $lnk=$page['redirect_url'];


        return view('livewire.pay')->extends('layouts.master')->section('content')->with('transaction',$lnk );



    }

}
