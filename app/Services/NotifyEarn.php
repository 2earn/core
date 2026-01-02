<?php

namespace App\Services;
 use App\Interfaces\INotifiable;
 use App\Interfaces\INotifyEarn;
 use App\Interfaces\IOperateurSms;

 class NotifyEarn implements INotifyEarn{

     public function sendNotify(INotifiable $notifiable)
     {
        return $notifiable->send();
     }
 }
