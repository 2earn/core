<?php

namespace Core\Models\MailOperator;

use Core\Enum\TypeEventNotificationEnum;
use Core\Interfaces\IMailOperator;
use Illuminate\Support\Facades\Mail;


class StandardMailOperator implements IMailOperator
{
    public TypeEventNotificationEnum $typeOperation;
    public $msg ;
    public $toEmail ;
    public $emailTitle ;
    public function __construct($toEmail, $emailTitle, $msg,TypeEventNotificationEnum $typeOperation)
    {
        $this->typeOperation = $typeOperation;
        $this->msg = $msg;
        $this->toEmail = $toEmail;
        $this->emailTitle = $emailTitle;
    }
    public function send()
    {
//        $PrefixMsg = "";
//        $locale = app()->getLocale();
//        switch ($locale){
//            case 'en':
//                switch ($this->typeOperation) {
//                    case TypeEventNotificationEnum::Inscri :
//                        $PrefixMsg = "Welcome to the 2earn.cash concept. your verification code is: ";
//                        break;
//                    case TypeEventNotificationEnum::Password  :
//                        $PrefixMsg = "Welcome to the 2earn.cash concept. you have just activated your registration, your password is: ";
//                        break;
//                    case TypeEventNotificationEnum::ToUpline  :
//                        $PrefixMsg = "Welcome to the 2earn.cash concept.Congratulations, he has joined your team the user with the phone number: ";
//                        break;
//                }
//                break;
//            case 'ar':
//                switch ($this->typeOperation) {
//                    case  TypeEventNotificationEnum::Inscri :
//                        $PrefixMsg = "مرحبًا بكم في منظومة  2earn.cash. رمز التفعيل الخاص بك هو: ";
//                        break;
//                    case TypeEventNotificationEnum::Password :
//                        $PrefixMsg = "مرحبًا بكم في 2earn.cash. لقد قمت بتفعيل تسجيلك ، كلمة العبور هي : ";
//                        break;
//                    case TypeEventNotificationEnum::ToUpline  :
//                        $PrefixMsg = "مبروك لقد انضم لفريقك المستخدم صاحب رقم الهاتف :  ";
//                        break;
//                }
//                break;
//            case 'fr':
//                switch ($this->typeOperation) {
//                    case TypeEventNotificationEnum::Inscri :
//                        $PrefixMsg = "Bienvenue au concept 2earn.cash. votre code de vérification est : ";
//                        break;
//                    case TypeEventNotificationEnum::Password  :
//                        $PrefixMsg = "Bienvenu au concept 2earn.cash. vous venez d'activer votre inscription, votre mot de passe est: ";
//                        break;
//                    case TypeEventNotificationEnum::ToUpline  :
//                        $PrefixMsg = "Bienvenu au concept 2earn.cash. Félicitations, il s’est joint à votre équipe l’utilisateur avec le numéro de téléphone: ";
//                        break;
//                }
//                break;
//        }
        $finalMsg =  $this->msg ;
        $emailTitle = $this->emailTitle;
        $toEmail = $this->toEmail;

        Mail::send('pwd_email', ['data' => $finalMsg], function ($message) use ($toEmail, $emailTitle) {
            $message->to($toEmail, '')->subject($emailTitle);
        });
    }
}
