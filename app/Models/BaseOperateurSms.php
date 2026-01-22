<?php
namespace App\Models;

use App\Interfaces\IOperateurSms;

class BaseOperateurSms  implements IOperateurSms {

    public function send( )
    {
        dd('no baseoperator implements found ');
    }
}
