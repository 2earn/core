<?php

namespace App\Services;

use App\Enums\TypeEventNotificationEnum;
use Illuminate\Support\Facades\Lang;

class MessageService
{
    /**
     * Get final message with prefix based on operation type
     *
     * @param string $mes Message content
     * @param TypeEventNotificationEnum $typeOperation Operation type
     * @return string Final formatted message
     */
    public function getMessageFinal(string $mes, TypeEventNotificationEnum $typeOperation): string
    {
        $PrefixMsg = $this->getPrefixForOperation($typeOperation);
        return $PrefixMsg . " " . $mes;
    }

    /**
     * Get final message with prefix based on operation type and specific language
     *
     * @param string $mes Message content
     * @param TypeEventNotificationEnum $typeOperation Operation type
     * @param string $newLang Language code to use
     * @return string Final formatted message
     */
    public function getMessageFinalByLang(string $mes, TypeEventNotificationEnum $typeOperation, string $newLang): string
    {
        $ancLang = app()->getLocale();
        app()->setLocale($newLang);

        $PrefixMsg = $this->getPrefixForOperation($typeOperation);

        app()->setLocale($ancLang);

        return $PrefixMsg . " " . $mes;
    }

    /**
     * Get prefix message for specific operation type
     *
     * @param TypeEventNotificationEnum $typeOperation
     * @return string
     */
    private function getPrefixForOperation(TypeEventNotificationEnum $typeOperation): string
    {
        return match ($typeOperation) {
            TypeEventNotificationEnum::Inscri => Lang::get('Prefix_SmsInscri'),
            TypeEventNotificationEnum::Password => Lang::get('Prefix_SmsPassword'),
            TypeEventNotificationEnum::ToUpline => Lang::get('Prefix_SmsToUpline'),
            TypeEventNotificationEnum::RequestDenied => Lang::get('Prefix_SmsRequestDenied'),
            TypeEventNotificationEnum::ForgetPassword => Lang::get('Prefix_SmsForgetPassword'),
            TypeEventNotificationEnum::OPTVerification => Lang::get('Prefix_SmsOPTVerification'),
            TypeEventNotificationEnum::VerifMail => Lang::get('Prefix_MailVerifMail'),
            TypeEventNotificationEnum::SendNewSMS => Lang::get('Prefix_SMSNewPass'),
            TypeEventNotificationEnum::RequestAccepted => Lang::get('Prefix sms request accepted'),
            TypeEventNotificationEnum::NewContactNumber => Lang::get('Prefix mail new contact number'),
            TypeEventNotificationEnum::none => "",
            default => "",
        };
    }
}

