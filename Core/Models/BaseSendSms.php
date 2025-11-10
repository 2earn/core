<?php
namespace Core\Models;

use Core\Interfaces\IOperateurSms;

class BaseSendSms {

    public function sendSms(IOperateurSms $operator)
    {
        $operator->send();
    }
}
