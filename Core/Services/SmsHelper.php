<?php

namespace Core\Services;

use Core\Enum\TypeEventNotificationEnum;
use Core\Services\settingsManager;


class SmsHelper
{
    private settingsManager $settingsManager;

    public function __construct(settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }
    public function sentSMS($destNum, $msg, $ccode, $idUser, TypeEventNotificationEnum $typeOperation)
    {
        $PrefixMsg = "";
        $finalMsg = "";
        $locale = app()->getLocale();
        $langage = "";
        switch ($locale) {
            case 'en':
                $langage = "English";
                switch($typeOperation){
                    case TypeEventNotificationEnum::Inscri :
                        $PrefixMsg = "Welcome to the 2earn.cash concept. your verification code is: ";
                        break;
                    case TypeEventNotificationEnum::Password  :
                        $PrefixMsg = "Welcome to the 2earn.cash concept. you have just activated your registration, your password is: ";
                        break;
                    case TypeEventNotificationEnum::ToUpline  :
                        $PrefixMsg = "Welcome to the 2earn.cash concept.Congratulations, he has joined your team the user with the phone number: ";
                        break;
                }
                break;
            case 'ar':
                $langage = "Arabic";
                switch($typeOperation){
                    case  TypeEventNotificationEnum::Inscri :
                        $PrefixMsg = "مرحبًا بكم في منظومة  2earn.cash. رمز التفعيل الخاص بك هو: ";
                        break;
                    case TypeEventNotificationEnum::Password :
                        $PrefixMsg = "مرحبًا بكم في 2earn.cash. لقد قمت بتفعيل تسجيلك ، كلمة العبور هي : ";
                        break;
                    case TypeEventNotificationEnum::ToUpline  :
                        $PrefixMsg = "مبروك لقد انضم لفريقك المستخدم صاحب رقم الهاتف :  ";
                        break;
                }
                break;
            case 'fr':
                $langage = "Frensh";
                switch($typeOperation){
                    case TypeEventNotificationEnum::Inscri :
                        $PrefixMsg = "Bienvenue au concept 2earn.cash. votre code de vérification est : ";
                        break;
                    case TypeEventNotificationEnum::Password  :
                        $PrefixMsg = "Bienvenu au concept 2earn.cash. vous venez d'activer votre inscription, votre mot de passe est: ";
                        break;
                    case TypeEventNotificationEnum::ToUpline  :
                        $PrefixMsg = "Bienvenu au concept 2earn.cash. Félicitations, il s’est joint à votre équipe l’utilisateur avec le numéro de téléphone: ";
                        break;
                }
                break;
        }
        $finalMsg = $PrefixMsg . $msg;
        if ($ccode == "216") {
            $postParams = [
                "login" => "AHY01",
                "pass" => "NVqJr7zq",
                "compte" => "Hypermedia",
                "dest_num" => $destNum,
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
            $postParams["msg"] = $finalMsg;
            $ch = $this->getCurl($postParams);
            $result = curl_exec($ch);
            return $result;
        } else {
            $text = $finalMsg;
            $bearer = 'e1c71a0e993e6cc173628d357a67d5b2';
            $taqnyt = new \TaqnyatSms($bearer);
            $body = $text;
            $recipients = [$destNum];
            $sender = '2earn.cash';
            $smsId = '' ;
            $message = $taqnyt->sendMsg($body, $recipients, $sender, $smsId);
        }
    }
    private function getCurl($postParams)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://41.226.33.133/wbmonitor/send/webapi/v2/send_ack.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "hypermedia:Tbmvkc8J");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        return $ch;
    }
    public function sentSMSPasswor($destNum, $msg, $ccode, $idUser)
    {
        $langage = null;
        if (session()->get('locale') != null) {
            if (session()->get('locale') == "en") {
                $langage = "English";
            } elseif (session()->get('locale') == "ar") {
                $langage = "Arabic";
            } elseif (session()->get('locale') == "fr") {
                $langage = "Frensh";
            }
        } else {
            $country = DB::table('countries')->where('phonecode', $ccode)->first();
            $langage = $country->langage;
        }
        if ($ccode == "216") {
            $postParams = [
                "login" => "AHY01",
                "pass" => "NVqJr7zq",
                "compte" => "Hypermedia",
                "dest_num" => $destNum,
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
            if ($langage == "English") {
                $postParams["msg"] = "Welcome to the 2earn.cash concept. you have just activated your registration, your password is: " . $msg;
            } elseif ($langage == "Frensh") {
                $postParams["msg"] = "Bienvenu au concept 2earn.cash. vous venez d'activer votre inscription, votre mot de passe est: " . $msg;
            } elseif ($langage == "Arabic") {
                $postParams["msg"] = "مرحبًا بكم في 2earn.cash. لقد قمت بتفعيل تسجيلك ، كلمة العبور هي : " . $msg;
            }
            $ch = $this->getCurl($postParams);
            $result = curl_exec($ch);
            return $result;

        } else {
            $country = DB::table('countries')->where('phonecode', $ccode)->first();
            if ($langage == "English") {
                $text = "Welcome to the 2earn.cash concept. you have just activated your registration, your password is: " . $msg;
            } elseif ($langage == "Frensh") {
                $text = "Bienvenu au concept 2earn.cash. vous venez d'activer votre inscription, votre mot de passe est: " . $msg;
            } elseif ($langage == "Arabic") {
                $text = "مرحبًا بكم في منظومة  2earn.cash. لقد قمت للتو بتفعيل تسجيلك ، كلمة العبور الخاصة بك هي : " . $msg;
            }

            $bearer = 'e1c71a0e993e6cc173628d357a67d5b2';
            $taqnyt = new \TaqnyatSms($bearer);

            $body = $text;
            $recipients = [$destNum];
            $sender = '2earn.cash';
            $smsId = '';

            $message = $taqnyt->sendMsg($body, $recipients, $sender, $smsId);
            print $message;

        }
    }


}
