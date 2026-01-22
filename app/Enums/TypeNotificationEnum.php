<?php
namespace App\Enums;

enum TypeNotificationEnum: string
{
    case SMS = "Sms";
    case MAIL = "Mail";

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}

