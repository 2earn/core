<?php
namespace App\Models\SmsOperators;
use App\Interfaces\IOperateurSms;
class SaSmsOperator implements IOperateurSms
{
    public function send( )
    {
       dd('sms send from sa');
    }
}
