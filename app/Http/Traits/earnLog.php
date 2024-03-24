<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

trait earnLog
{

    public function earnDebug($message)
    {
        $clientIP = "";
        try {
            $clientIP = request()->ip() . "   " . request()->server->get('HTTP_SEC_CH_UA');
        } catch (err) {
        }

        Log::channel('earnDebug')->debug('Client IP : ' . $clientIP . 'Details-Log : ' . $message);
    }


    public function earnException($message)
    {
        $clientIP = "";
        try {
            $clientIP = request()->ip() . "   " . request()->server->get('HTTP_SEC_CH_UA');
        } catch (err) {
        }
        Log::channel('earnException')->debug('Client IP : ' . $clientIP . 'Ex : ' . $message);
    }

    public function earnDebugSms($message)
    {
        $clientIP = "";
        try {
            $clientIP = request()->ip() . "   " . request()->server->get('HTTP_SEC_CH_UA');
        } catch (err) {
        }
        Log::channel('earnDebugSms')->debug('Client IP : ' . $clientIP . 'Ex : ' . $message);
    }

}
