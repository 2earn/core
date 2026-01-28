<?php

namespace Tests\Unit\Services;

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
     * Test getUserNotificationSettings returns user settings
     */
    public function test_get_user_notification_settings_returns_settings()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserNotificationSettings::factory()->count(3)->create([
            'idUser' => $user->id,
        ]);

        // Act
        $result = $this->userNotificationSettingService->getUserNotificationSettings($user->id);

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserNotificationSettings returns empty collection when no settings
     */
    public function test_get_user_notification_settings_returns_empty_when_none()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingService->getUserNotificationSettings($user->id);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test updateSetting updates notification setting
     */
    public function test_update_setting_updates_successfully()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $setting = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 5,
            'value' => 0,
        ]);

        // Act
        $result = $this->userNotificationSettingService->updateSetting(5, $user->id, 1);

        // Assert
        $this->assertTrue($result);
        $setting->refresh();
        $this->assertEquals(1, $setting->value);
    }

    /**
     * Test updateSetting returns false when setting not found
     */
    public function test_update_setting_returns_false_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingService->updateSetting(999, $user->id, 1);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test updateMultipleSettings updates multiple settings in bulk
     */
    public function test_update_multiple_settings_updates_bulk()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $setting1 = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 1,
            'value' => 0,
        ]);
        $setting2 = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 2,
            'value' => 0,
        ]);

        $settings = [
            ['idNotification' => 1, 'idUser' => $user->id, 'value' => 1],
            ['idNotification' => 2, 'idUser' => $user->id, 'value' => 1],
        ];

        // Act
        $result = $this->userNotificationSettingService->updateMultipleSettings($settings);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Notifications setting saved successfully', $result['message']);
        $this->assertEquals(2, $result['updatedCount']);
        $setting1->refresh();
        $setting2->refresh();
        $this->assertEquals(1, $setting1->value);
        $this->assertEquals(1, $setting2->value);
    }

    /**
     * Test updateMultipleSettings handles empty array
     */
    public function test_update_multiple_settings_handles_empty_array()
    {
        // Act
        $result = $this->userNotificationSettingService->updateMultipleSettings([]);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['updatedCount']);
    }

    /**
     * Test getSettingValue returns setting value
     */
    public function test_get_setting_value_returns_value()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $setting = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'value' => 1,
        ]);

        // Act
        $result = $this->userNotificationSettingService->getSettingValue($user->id, $setting->id);

        // Assert
        $this->assertEquals(1, $result);
    }

    /**
     * Test getSettingValue returns null when not found
     */
    public function test_get_setting_value_returns_null_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingService->getSettingValue($user->id, 9999);

        // Assert
        $this->assertNull($result);
    }
}
