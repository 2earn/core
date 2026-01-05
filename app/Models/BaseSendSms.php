<?php
namespace App\Models;

use App\Interfaces\IOperateurSms;

class BaseSendSms {

    public function sendSms(IOperateurSms $operator)
    {
        $operator->send();
    }
}
