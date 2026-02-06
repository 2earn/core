<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserNotificationSettings;
use App\Services\UserNotificationSettingsService;
use Tests\TestCase;

class UserNotificationSettingsServiceTest extends TestCase
{

    protected UserNotificationSettingsService $userNotificationSettingsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userNotificationSettingsService = new UserNotificationSettingsService();
    }

    /**
     * Test updateNotificationSetting method
     */
    public function test_update_notification_setting_works()
    {
        // Arrange
        $user = User::factory()->create();
        $setting = UserNotificationSettings::factory()->create([
            'idUser' => $user->idUser,
            'idNotification' => 1,
            'value' => 0,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->updateNotificationSetting(
            $user->idUser,
            1,
            1
        );

        // Assert
        $this->assertGreaterThan(0, $result);
        $setting->refresh();
        $this->assertEquals(1, $setting->value);
    }

    /**
     * Test getNotificationSetting method
     */
    public function test_get_notification_setting_works()
    {
        // Arrange
        $user = User::factory()->create();
        $setting = UserNotificationSettings::factory()->create([
            'idUser' => $user->idUser,
            'idNotification' => 5,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->getNotificationSetting(
            $user->idUser,
            5
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(UserNotificationSettings::class, $result);
        $this->assertEquals($setting->id, $result->id);
    }

    /**
     * Test getUserNotificationSettings method
     */
    public function test_get_user_notification_settings_works()
    {
        // Arrange
        $user = User::factory()->create();
        // Create 5 settings with unique idNotification values to avoid constraint violations
        for ($i = 1; $i <= 5; $i++) {
            UserNotificationSettings::factory()->create([
                'idUser' => $user->idUser,
                'idNotification' => $i,
            ]);
        }
        UserNotificationSettings::factory()->count(3)->create();

        // Act
        $result = $this->userNotificationSettingsService->getUserNotificationSettings($user->idUser);

        // Assert
        $this->assertCount(5, $result);
        $this->assertTrue($result->every(fn($s) => $s->idUser == $user->idUser));
    }

    /**
     * Test upsertNotificationSetting method
     */
    public function test_upsert_notification_setting_works()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        // Test create
        $result = $this->userNotificationSettingsService->upsertNotificationSetting(
            $user->idUser,
            10,
            1
        );

        // Assert
        $this->assertInstanceOf(UserNotificationSettings::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
        $this->assertEquals(10, $result->idNotification);
        $this->assertEquals(1, $result->value);

        // Act
        // Test update
        $result2 = $this->userNotificationSettingsService->upsertNotificationSetting(
            $user->idUser,
            10,
            0
        );

        // Assert
        $this->assertEquals($result->id, $result2->id);
        $this->assertEquals(0, $result2->value);
    }

    /**
     * Test deleteUserNotificationSettings method
     */
    public function test_delete_user_notification_settings_works()
    {
        // Arrange
        $user = User::factory()->create();
        // Create 4 settings with unique idNotification values to avoid constraint violations
        for ($i = 11; $i <= 14; $i++) {
            UserNotificationSettings::factory()->create([
                'idUser' => $user->idUser,
                'idNotification' => $i,
            ]);
        }

        // Act
        $result = $this->userNotificationSettingsService->deleteUserNotificationSettings($user->idUser);

        // Assert
        $this->assertEquals(4, $result);
        $this->assertEquals(0, UserNotificationSettings::where('idUser', $user->idUser)->count());
    }
}
