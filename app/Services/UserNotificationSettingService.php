<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserNotificationSettingService
{
    /**
     * Get notification settings for a user
     *
     * @param string $idUser User's business ID
     * @return Collection
     */
    public function getUserNotificationSettings(string $idUser): Collection
    {
        try {
            return DB::table('user_notification_setting')
                ->where('idUser', $idUser)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching user notification settings: ' . $e->getMessage(), [
                'idUser' => $idUser
            ]);
            return new Collection();
        }
    }

    /**
     * Update a user notification setting
     *
     * @param int $idNotification Notification ID
     * @param string $idUser User's business ID
     * @param mixed $value Setting value
     * @return bool
     */
    public function updateSetting(int $idNotification, string $idUser, $value): bool
    {
        try {
            $updated = DB::table('user_notification_setting')
                ->where('idNotification', $idNotification)
                ->where('idUser', $idUser)
                ->update(['value' => $value]);

            return $updated > 0;
        } catch (\Exception $e) {
            Log::error('Error updating user notification setting: ' . $e->getMessage(), [
                'idNotification' => $idNotification,
                'idUser' => $idUser,
                'value' => $value
            ]);
            return false;
        }
    }

    /**
     * Update multiple user notification settings in bulk
     *
     * @param array $settings Array of settings with idNotification, idUser, and value
     * @return array Result array with success status and message
     */
    public function updateMultipleSettings(array $settings): array
    {
        try {
            DB::beginTransaction();

            $updatedCount = 0;
            foreach ($settings as $setting) {
                $result = $this->updateSetting(
                    $setting['idNotification'],
                    $setting['idUser'],
                    $setting['value']
                );

                if ($result) {
                    $updatedCount++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Notifications setting saved successfully',
                'updatedCount' => $updatedCount
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating multiple notification settings: ' . $exception->getMessage(), [
                'settings' => $settings
            ]);

            return [
                'success' => false,
                'message' => 'Notifications setting saving failed'
            ];
        }
    }

    /**
     * Get a specific notification setting value
     *
     * @param string $idUser User's business ID
     * @param int $settingId Setting ID
     * @return mixed|null
     */
    public function getSettingValue(string $idUser, int $settingId)
    {
        try {
            $setting = DB::table('user_notification_setting')
                ->where('idUser', $idUser)
                ->where('id', $settingId)
                ->first();

            return $setting ? $setting->value : null;
        } catch (\Exception $e) {
            Log::error('Error fetching notification setting value: ' . $e->getMessage(), [
                'idUser' => $idUser,
                'settingId' => $settingId
            ]);
            return null;
        }
    }
}
