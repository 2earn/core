<?php
namespace App\Interfaces;

interface INotifyEarn{
    public function sendNotify(
        INotifiable $notifiable
    );
}
