<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserNotificationSettings;
use App\Services\UserNotificationSettingService;
use Tests\TestCase;

class UserNotificationSettingServiceTest extends TestCase
{

    protected UserNotificationSettingService $userNotificationSettingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userNotificationSettingService = new UserNotificationSettingService();
    }

    /**
     * Test getUserNotificationSettings method
     */
    public function test_get_user_notification_settings_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserNotificationSettings::factory()->count(5)->create(['idUser' => $user->idUser]);
        UserNotificationSettings::factory()->count(3)->create();

        // Act
        $result = $this->userNotificationSettingService->getUserNotificationSettings($user->idUser);

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test updateSetting method
     */
    public function test_update_setting_works()
    {
        // Arrange
        $user = User::factory()->create();
        $setting = UserNotificationSettings::factory()->create([
            'idUser' => $user->idUser,
            'idNotification' => 7,
            'value' => 0,
        ]);

        // Act
        $result = $this->userNotificationSettingService->updateSetting(7, $user->idUser, 1);

        // Assert
        $this->assertTrue($result);
        $setting->refresh();
        $this->assertEquals(1, $setting->value);
    }

    /**
     * Test updateMultipleSettings method
     */
    public function test_update_multiple_settings_works()
    {
        // Arrange
        $user = User::factory()->create();
        $settings = [
            UserNotificationSettings::factory()->create(['idUser' => $user->idUser, 'idNotification' => 1]),
            UserNotificationSettings::factory()->create(['idUser' => $user->idUser, 'idNotification' => 2]),
            UserNotificationSettings::factory()->create(['idUser' => $user->idUser, 'idNotification' => 3]),
        ];

        $updateData = [
            ['idNotification' => 1, 'idUser' => $user->idUser, 'value' => 1],
            ['idNotification' => 2, 'idUser' => $user->idUser, 'value' => 0],
            ['idNotification' => 3, 'idUser' => $user->idUser, 'value' => 1],
        ];

        // Act
        $result = $this->userNotificationSettingService->updateMultipleSettings($updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['updatedCount']);
    }

    /**
     * Test getSettingValue method
     */
    public function test_get_setting_value_works()
    {
        // Arrange
        $user = User::factory()->create();
        $setting = UserNotificationSettings::factory()->create([
            'idUser' => $user->idUser,
            'value' => 1,
        ]);

        // Act
        $result = $this->userNotificationSettingService->getSettingValue($user->idUser, $setting->id);

        // Assert
        $this->assertEquals(1, $result);
    }
}
