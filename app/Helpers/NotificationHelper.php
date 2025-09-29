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
        return 'notifications.' . Str::snake(class_basename($notification->type));
    }

    public static function localizeUrl(string $url, string $currentLocale): string
    {
        $pattern = '#/(?:' . implode('|', config('app.available_locales')) . ')(?=/|$)#';
        return preg_replace($pattern, "/{$currentLocale}", $url);
    }


}
