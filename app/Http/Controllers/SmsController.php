<?php

namespace App\Http\Controllers;

use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Http\Request as Req;

class SmsController extends Controller
{
    public function sendSMS(Req $request, settingsManager $settingsManager)
    {
        $settingsManager->NotifyUser($request->user, TypeEventNotificationEnum::none, ['msg' => $request->msg, 'type' => TypeNotificationEnum::SMS]);
    }
}
