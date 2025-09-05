<?php

namespace Core\Models\SmsOperators;

use Core\Enum\TypeEventNotificationEnum;
use Core\Interfaces\IOperateurSms;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class InternationalOperatorSms implements IOperateurSms
{
    const URL = "https://www.msegat.com/gw/sendsms.php";
    public TypeEventNotificationEnum $typeOperation;
    public $msg;
    public $destination;

    public function __construct($destNum, $msg, TypeEventNotificationEnum $typeOperation)
    {
        $this->typeOperation = $typeOperation;
        $this->msg = $msg;
        $this->destination = $destNum;
    }

    public function send()
    {
        if (App::environment('local')) {
            return 'No sms for local envs';
        }
        $userSender = "2earn.cash";
        $numberFinal = ltrim($this->destination, "0");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $fields = "{\"userName\": \"tounsi.ghazi\",\"numbers\": \"$numberFinal\",\"userSender\": \"$userSender\",\"apiKey\": \"771141af8aab0aee65171912023cb3c8\",\"msg\": \"$this->msg\" }";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        curl_getinfo($ch);
        curl_close($ch);
        Log::notice('InternationalOperatorSms : ' . json_encode($response));;
        return $response;
    }


}
