<?php

namespace Core\Models\MailOperator;

use App\Enums\TypeEventNotificationEnum;
use Core\Interfaces\IMailOperator;
use Illuminate\Support\Facades\Mail;


class StandardMailOperator implements IMailOperator
{
    public TypeEventNotificationEnum $typeOperation;
    public $msg ;
    public $toEmail ;
    public $emailTitle ;
    public function __construct($toEmail, $emailTitle, $msg,TypeEventNotificationEnum $typeOperation)
    {
        $this->typeOperation = $typeOperation;
        $this->msg = $msg;
        $this->toEmail = $toEmail;
        $this->emailTitle = $emailTitle;
    }
    public function send()
    {
        $finalMsg =  $this->msg ;
        $emailTitle = $this->emailTitle;
        $toEmail = $this->toEmail;

        Mail::send('pwd_email', ['data' => $finalMsg], function ($message) use ($toEmail, $emailTitle) {
            $message->to($toEmail, '')->subject($emailTitle);
        });
    }
}
