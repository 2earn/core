<?php

namespace Core\Enum;

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
    case VerifMail ="VerifyEmail";
    case RequestAccepted = "RequestAccepted";
    case NewContactNumber = "NewContactNumber";
    case none = "none";

    public function getSettingSms(): NotificationSettingEnum
    {
        return match ($this) {
            TypeEventNotificationEnum::Inscri => NotificationSettingEnum::cont_inscri_sms,
            TypeEventNotificationEnum::Password, TypeEventNotificationEnum::SendNewSMS => NotificationSettingEnum::change_pwd_sms,
            TypeEventNotificationEnum::ToUpline => NotificationSettingEnum::cont_inscri_sms,
            TypeEventNotificationEnum::VerifMail =>NotificationSettingEnum::none,
            TypeEventNotificationEnum::NewContactNumber =>NotificationSettingEnum::none,
            TypeEventNotificationEnum::OPTVerification =>NotificationSettingEnum::none,
        };
    }

    public function getSettingMail(): NotificationSettingEnum
    {
        return match ($this) {
            TypeEventNotificationEnum::Inscri => NotificationSettingEnum::cont_inscri_sms,
            TypeEventNotificationEnum::Password => NotificationSettingEnum::change_pwd_sms,
            TypeEventNotificationEnum::ToUpline => NotificationSettingEnum::cont_inscri_email,
            TypeEventNotificationEnum::SendNewSMS => NotificationSettingEnum::none,
            TypeEventNotificationEnum::VerifMail =>NotificationSettingEnum::none,
            TypeEventNotificationEnum::NewContactNumber =>NotificationSettingEnum::none,
            TypeEventNotificationEnum::OPTVerification =>NotificationSettingEnum::none,
        };
    }
}
