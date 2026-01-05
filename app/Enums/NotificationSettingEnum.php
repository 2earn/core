<?php

namespace App\Enums;

enum NotificationSettingEnum: int
{
    case none = -1;
    case change_pwd_sms = 1;
    case validate_phone_email = 2;
    case iden_valide_sms = 3;
    case new_cnx_sms = 4;
    case cont_inscri_sms = 5;
    case cont_inscri_email = 6;
    case invit_achat_email = 7;
    case cart_validate_email = 8;
    case tree_dead_sms = 9;
    case delivery_sms = 10;
    case discount_sms = 11;
    case rappel_formation_sms = 12;
    case campagnes_sms = 14;
    case discount_sms_p = 15;
    case discount_email_p = 16;
    case discount_plateforme_p = 17;
    case cashBack80_sms = 18;
    case SMSByWeek = 19;
}
