<?php

namespace Core\Services;

use Core\Enum\OperateurSmsEnum;
use Core\Enum\TypeNotificationEnum;

use Core\Enum\TypeEventNotificationEnum;
use Core\Interfaces\INotifiable;
use Core\Interfaces\INotifyEarn;
use Core\Models\MailOperator\StandardMailOperator;
use Core\Models\Notification\DefaultNotification;
use Core\Models\Notification\MailNotification;
use Core\Models\Notification\SmsNotification;
use Core\Models\SmsOperators\InternationalOperatorSms;
use Core\Models\SmsOperators\SaSmsOperator;
use Core\Models\SmsOperators\TunisieOperatorSms;
use Illuminate\Http\RedirectResponse;


class NotifyHelper
{
    private INotifyEarn $notifyEarn;
    private INotifiable $notifiable;

    public function __construct(INotifyEarn $notifyEarn)
    {
        $this->notifyEarn = $notifyEarn;
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
                break;
            case TypeNotificationEnum::MAIL:
                $this->notifiable = new MailNotification(
                    new  StandardMailOperator($params["toMail"], $params["emailTitle"], $params["msg"], $typeEvent)
                );
                break;
        }
        $resul = $this->notifyEarn->sendNotify($this->notifiable);
        return $resul;
    }

}
