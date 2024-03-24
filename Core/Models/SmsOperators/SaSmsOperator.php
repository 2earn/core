<?php
namespace  Core\Models\SmsOperators ;
use Core\Interfaces\IOperateurSms;
class SaSmsOperator implements IOperateurSms
{
    public function send( )
    {
       dd('sms send from sa');
    }
}
