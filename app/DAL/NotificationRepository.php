<?php

namespace App\DAL;


use Core\Enum\NotificationSettingEnum;
use Core\Interfaces\INotificationRepository;
use Core\Models\NotificationsSettings;
use Illuminate\Support\Facades\DB;
use Core\Models\UserNotificationSettings;

class  NotificationRepository implements INotificationRepository
{

    public function getNotificationSettingByIdUser($idUser)
    {
        $ss = DB::table('user_notification_setting')
            ->join('notifications_settings', 'notifications_settings.id', '=', 'user_notification_setting.idNotification')
            ->where('idUser', '=', $idUser)->get();
        return $ss;
    }
    public function deleteNotificationByIdUser($idUser)
    {
        $ss = UserNotificationSettings::where('idUser',$idUser)
            ->delete();
    }
    public  function createUserNotificationSetting(UserNotificationSettings $notificationSetting)
    {
        UserNotificationSettings::create([
            "idNotification" =>  $notificationSetting->idNotification,
            "value" => $notificationSetting->value,
            "idUser" => $notificationSetting->idUser
        ]);
    }
    public function getAllNotification()
    {
        return NotificationsSettings::all();
    }
    public  function insertuserNotification($data)
    {
        UserNotificationSettings::insert($data);
    }

    public function getNombreNotif()
    {
        return 12 ;
    }
}
