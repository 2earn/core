<?php
namespace Core\Interfaces;

interface INotifyEarn{
    public function sendNotify(
        INotifiable $notifiable
    );
}
