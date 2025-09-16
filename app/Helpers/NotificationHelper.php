<?php

namespace App\Helpers;

use Core\Enum\NotificationSettingEnum;

class NotificationHelper
{
    public static function getLabel(NotificationSettingEnum $enum): string
    {
        return __('notifications.settings.' . $enum->name);
    }


    public static function format($notification)
    {
        $notificationText = '';
        switch ($notification->type) {
            case 'App\Notifications\contact_registred':
                $notificationText = Lang::get('New contact registred') . ' ' . $notification->data['fullphone_number'];
                break;
            default:
                echo trans('please fill it in formatNotification');
        }
        return $notificationText;
    }
}
