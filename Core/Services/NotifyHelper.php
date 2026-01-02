<?php

namespace Core\Services;

use App\Enums\OperateurSmsEnum;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\Sms;
use Core\Interfaces\INotifiable;
use Core\Interfaces\INotifyEarn;
use Core\Models\MailOperator\StandardMailOperator;
use Core\Models\Notification\DefaultNotification;
use Core\Models\Notification\MailNotification;
use Core\Models\Notification\SmsNotification;
use Core\Models\SmsOperators\InternationalOperatorSms;
use Core\Models\SmsOperators\SaSmsOperator;
use Core\Models\SmsOperators\TunisieOperatorSms;
use Illuminate\Support\Facades\Log;


class NotifyHelper
{
    private INotifiable $notifiable;

    public function __construct(private INotifyEarn $notifyEarn)
    {
    }

    public function removeLeadingZeros($phoneNumber)
    {
        return preg_replace('/^00/', '', $phoneNumber);
    }

    /**
     * Returns void
     *
     * @param TypeNotificationEnum $typeNotificationEnum
     * @param OperateurSmsEnum|null $operateurSms
     * @param TypeEventNotificationEnum $typeEvent
     * @param array|null $params
     * @return void
     * "notifyuser" function to notify user with any type notification according to the type :
     *
     */
    public function notifyuser(
        TypeNotificationEnum      $typeNotificationEnum,
        OperateurSmsEnum          $operateurSms = null,
        TypeEventNotificationEnum $typeEvent,
        array                     $params = null
    )
    {
        switch ($typeNotificationEnum) {
            case TypeNotificationEnum::SMS:
                if ($operateurSms == null) return;


                $this->notifiable = match ($operateurSms) {
                    OperateurSmsEnum::Tunisie => new SmsNotification(
                        new TunisieOperatorSms($params["fullNumber"], $params["msg"], $typeEvent)
                    ),
                    OperateurSmsEnum::sa => new SmsNotification(
                        new SaSmsOperator()
                    ),
                    OperateurSmsEnum::international => new SmsNotification(new InternationalOperatorSms($params["fullNumber"], $params["msg"], $typeEvent)),
                    default => new  DefaultNotification(),

                };

                try {
                    Sms::create([
                        'message' => $params["msg"] ?? '',
                        'destination_number' => $params["fullNumber"] ?? '',
                        'source_number' => '2earn.cash',
                        'created_by' => $params["userId"] ?? null,
                        'updated_by' => $params["userId"] ?? null,
                    ]);
                    Log::info("NotifyHelper: SMS record created for " . ($params["fullNumber"] ?? 'unknown'));
                } catch (\Exception $e) {
                    Log::error("NotifyHelper: Failed to create SMS record: " . $e->getMessage());
                }
                break;
            case
            TypeNotificationEnum::MAIL:
                $this->notifiable = new MailNotification(
                    new  StandardMailOperator($params["toMail"], $params["emailTitle"], $params["msg"], $typeEvent)
                );
                break;
        }
        return $this->notifyEarn->sendNotify($this->notifiable);
    }

}
