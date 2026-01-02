<?php

namespace App\Models\SmsOperators;

use App\Enums\TypeEventNotificationEnum;
use App\Interfaces\IOperateurSms;
use Illuminate\Support\Facades\Log;

class TunisieOperatorSms implements IOperateurSms
{
    CONST URL = "https://41.226.33.133/wbmonitor/send/webapi/v2/send_ack.php";
    CONST USERPWD = "hypermedia:Tbmvkc8J";
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
        if (app()->environment('local')) {
            return 'No sms for local envs';
        }
        $postParams = [
            "login" => "AHY01",
            "pass" => "NVqJr7zq",
            "compte" => "Hypermedia",
            "dest_num" => $this->destination,
            "type" => 0,
            "auto_detect" => 1,
            "label" => "2earn.cash",
            "ref" => "test",
            "application" => "NG Trend",
            "res_type" => "JSON",
            "op" => 1,
            "dt" => "09/03/2022",
            "hr" => "00",
            "mn" => "00",
        ];
        $postParams["msg"] = $this->msg;
        $ch = $this->getCurl($postParams);
        $result = curl_exec($ch);
        Log::notice('TunisieOperatorSms : ' . json_encode($result));
        return $result;
    }

    private function getCurl($postParams)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, self::USERPWD);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        return $ch;
    }
}
