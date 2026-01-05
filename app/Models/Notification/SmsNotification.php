<?php

namespace App\Models\Notification;


use App\Interfaces\INotifiable;
use App\Interfaces\IOperateurSms;

class SmsNotification implements INotifiable
{
    private IOperateurSms $operateurSms;
    public function __construct(IOperateurSms $operateurSms)
    {
        $this->operateurSms = $operateurSms ;
    }
    public function send()
    {
        return $this->operateurSms->send();

    }
}
