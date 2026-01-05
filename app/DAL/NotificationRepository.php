<?php

namespace App\DAL;


use App\Interfaces\INotificationRepository;
use App\Models\NotificationsSettings;
use App\Models\UserNotificationSettings;
use Illuminate\Support\Facades\DB;

class  NotificationRepository implements INotificationRepository
{

    public function getNotificationSettingByIdUser($idUser)
    {
        return DB::table('user_notification_setting')
            ->join('notifications_settings', 'notifications_settings.id', '=', 'user_notification_setting.idNotification')
            ->where('idUser', '=', $idUser)->get();

    }

    public function deleteNotificationByIdUser($idUser)
    {
        UserNotificationSettings::where('idUser', $idUser)->delete();
    }

    public function createUserNotificationSetting(UserNotificationSettings $notificationSetting)
    {
        UserNotificationSettings::create([
            "idNotification" => $notificationSetting->idNotification,
            "value" => $notificationSetting->value,
            "idUser" => $notificationSetting->idUser
        ]);
    }

    public function getAllNotification()
    {
        return NotificationsSettings::all();
    }

    public function insertuserNotification($data)
    {
        UserNotificationSettings::insert($data);
    }

    public function getNombreNotif()
    {
        return 12;
    }
}
