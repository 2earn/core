<?php

namespace App\Services;

use Core\Models\UserNotificationSettings;

class UserNotificationSettingsService
{
    /**
     * Update notification setting value for a user
     *
     * @param string $idUser
     * @param int $notificationId
     * @param mixed $value
     * @return int Number of rows updated
     */
    public function updateNotificationSetting(string $idUser, int $notificationId, $value): int
    {
        return UserNotificationSettings::where('idUser', $idUser)
            ->where('idNotification', $notificationId)
            ->update(['value' => $value]);
    }

    /**
     * Get notification setting for a user
     *
     * @param string $idUser
     * @param int $notificationId
     * @return UserNotificationSettings|null
     */
    public function getNotificationSetting(string $idUser, int $notificationId): ?UserNotificationSettings
    {
        return UserNotificationSettings::where('idUser', $idUser)
            ->where('idNotification', $notificationId)
            ->first();
    }

    /**
     * Get all notification settings for a user
     *
     * @param string $idUser
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNotificationSettings(string $idUser)
    {
        return UserNotificationSettings::where('idUser', $idUser)->get();
    }

    /**
     * Create or update notification setting
     *
     * @param string $idUser
     * @param int $notificationId
     * @param mixed $value
     * @return UserNotificationSettings
     */
    public function upsertNotificationSetting(string $idUser, int $notificationId, $value): UserNotificationSettings
    {
        return UserNotificationSettings::updateOrCreate(
            [
                'idUser' => $idUser,
                'idNotification' => $notificationId
            ],
            [
                'value' => $value
            ]
        );
    }

    /**
     * Delete all notification settings for a user
     *
     * @param string $idUser
     * @return int Number of rows deleted
     */
    public function deleteUserNotificationSettings(string $idUser): int
    {
        return UserNotificationSettings::where('idUser', $idUser)->delete();
    }
}

