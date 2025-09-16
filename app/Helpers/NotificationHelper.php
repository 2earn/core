<?php

namespace App\Helpers;

use Core\Enum\NotificationSettingEnum;

class NotificationHelper
{
    public static function getLabel(NotificationSettingEnum $enum): string
    {
        return __('notifications.settings.' . $enum->name);
    }
}
