<?php
namespace Core\Models;

use Core\Interfaces\IOperateurSms;

class BaseOperateurSms  implements IOperateurSms {

    public function send( )
    {
        dd('no baseoperator implements found ');
    }
}
