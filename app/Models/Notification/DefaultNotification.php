<?php
namespace App\Models\Notification;
use App\Interfaces\INotifiable;
class DefaultNotification implements INotifiable
{

    public function send( )
    {
        dd("no implement default notification");
        return "";
    }
}
