<?php

namespace Core\Models\Notification;


use Core\Interfaces\INotifiable;
use Core\Interfaces\IOperateurSms;

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
