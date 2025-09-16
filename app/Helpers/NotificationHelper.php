<?php

namespace App\Helpers;

use Core\Enum\NotificationSettingEnum;
use Illuminate\Support\Str;

class NotificationHelper
{
    public static function getLabel(NotificationSettingEnum $enum): string
    {
        return __('notifications.settings.' . $enum->name);
    }


    public static function getTemplate($notification)
    {
        return 'notifications.'.Str::snake(class_basename($notification->type));
    }


}
