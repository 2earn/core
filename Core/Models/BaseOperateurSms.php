<?php
namespace Core\Models;

use Core\Enum\OperateurSmsEnum;
use Core\Interfaces\IOperateurSms;
use function PHPUnit\Framework\throwException;

class BaseOperateurSms  implements IOperateurSms {

    public function send( )
    {
        dd('no baseoperator implements found ');
    }
}
