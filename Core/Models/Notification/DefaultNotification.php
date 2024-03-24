<?php
namespace Core\Models\Notification;
use Core\Interfaces\INotifiable;
class DefaultNotification implements INotifiable
{

    public function send( )
    {
        dd("no implement default notification");
        return "";
    }
}
