<?php
namespace Core\Models\Notification;



use Core\Interfaces\IMailOperator;
use Core\Interfaces\INotifiable;
use Illuminate\Support\Facades\Mail;


class MailNotification implements INotifiable
{
    private IMailOperator $operateurMail;
    public function __construct(IMailOperator $operateurMail)
    {
        $this->operateurMail = $operateurMail ;
    }

    public function send()
    {
        $this->operateurMail->send();
        return "" ;
    }
}
