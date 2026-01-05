<?php

namespace App\Enums;

use App\Enums\NotificationSettingEnum;
use phpDocumentor\Reflection\Type;

enum TypeEventNotificationEnum: string
{
    case Inscri = "CodeInscription";
    case Password = "Password";
    case ToUpline = "ToUpline";
    case RequestDenied = "RequestDenied";
    case ForgetPassword = "ForgetPassword";
    case ExchangeCashBFS = "ExchangeCashBFS";
    case OPTVerification = "OPTVerification";
    case SendNewSMS = "SendNewSMS";
    case VerifMail = "VerifyEmail";
    case RequestAccepted = "RequestAccepted";
    case NewContactNumber = "NewContactNumber";
    case none = "none";

    public function getSettingSms(): NotificationSettingEnum
    {
        return match ($this) {
            \App\Enums\TypeEventNotificationEnum::Inscri => NotificationSettingEnum::cont_inscri_sms,
            \App\Enums\TypeEventNotificationEnum::Password, \App\Enums\TypeEventNotificationEnum::SendNewSMS => NotificationSettingEnum::change_pwd_sms,
            \App\Enums\TypeEventNotificationEnum::ToUpline => NotificationSettingEnum::cont_inscri_sms,
            \App\Enums\TypeEventNotificationEnum::VerifMail => NotificationSettingEnum::none,
            \App\Enums\TypeEventNotificationEnum::NewContactNumber => NotificationSettingEnum::none,
            \App\Enums\TypeEventNotificationEnum::OPTVerification => NotificationSettingEnum::none,
        };
    }

    public function getSettingMail(): NotificationSettingEnum
    {
        return match ($this) {
            \App\Enums\TypeEventNotificationEnum::Inscri => NotificationSettingEnum::cont_inscri_sms,
            \App\Enums\TypeEventNotificationEnum::Password => NotificationSettingEnum::change_pwd_sms,
            \App\Enums\TypeEventNotificationEnum::ToUpline => NotificationSettingEnum::cont_inscri_email,
            \App\Enums\TypeEventNotificationEnum::SendNewSMS => NotificationSettingEnum::none,
            \App\Enums\TypeEventNotificationEnum::VerifMail => NotificationSettingEnum::none,
            \App\Enums\TypeEventNotificationEnum::NewContactNumber => NotificationSettingEnum::none,
            \App\Enums\TypeEventNotificationEnum::OPTVerification => NotificationSettingEnum::none,
        };
    }
}

