<?php

namespace Core\Services;
 use Core\Interfaces\INotifiable;
 use Core\Interfaces\INotifyEarn;
 use Core\Interfaces\IOperateurSms;

 class NotifyEarn implements INotifyEarn{

     public function sendNotify(INotifiable $notifiable)
     {
        return $notifiable->send();
     }
 }
