<?php
namespace Core\Models;

use Core\Interfaces\IOperateurSms;
use Core\Interfaces\ISendSms;
use function PHPUnit\Framework\throwException;

class BaseSendSms  implements ISendSms {

    public function sendSms(IOperateurSms $operator)
    {
        $operator->send();
    }
}
