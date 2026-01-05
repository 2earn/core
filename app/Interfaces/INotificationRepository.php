<?php
namespace App\Interfaces;

use App\Models\UserNotificationSettings;

interface  INotificationRepository {
    public function getNotificationSettingByIdUser($idUser);
    public function deleteNotificationByIdUser($idUser) ;
    public  function createUserNotificationSetting(UserNotificationSettings $notificationSetting);
    public function getAllNotification();
    public  function insertuserNotification($data) ;
    public function getNombreNotif();
}
