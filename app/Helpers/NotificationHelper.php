<?php

namespace App\Helpers;

use App\Enums\NotificationSettingEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationHelper
{
    public static function getLabel(NotificationSettingEnum $enum): string
    {
        return __('notifications.settings.' . $enum->name);
    }


    public static function getTemplate($notification)
    {
        Log::info('notifications.' . Str::snake(class_basename($notification->type)));
        return 'notifications.' . Str::snake(class_basename($notification->type));
    }


    public static function localizeUrl(string $url, string $currentLocale): string
    {
        $pattern = '#/(?:' . implode('|', array_keys(config('app.available_locales'))) . ')(?=/|$)#';

        return preg_replace($pattern, "/{$currentLocale}", $url);
    }
}
