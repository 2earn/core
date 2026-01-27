<?php

namespace Tests\Unit\Services;

use App\Services\UserNotificationSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserNotificationSettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserNotificationSettingsService $userNotificationSettingsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userNotificationSettingsService = new UserNotificationSettingsService();
    }

    /**
     * Test updateNotificationSetting updates setting value
     */
    public function test_update_notification_setting_updates_value()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $setting = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 1,
            'value' => 0,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->updateNotificationSetting($user->id, 1, 1);

        // Assert
        $this->assertGreaterThan(0, $result);
        $setting->refresh();
        $this->assertEquals(1, $setting->value);
    }

    /**
     * Test updateNotificationSetting returns zero when not found
     */
    public function test_update_notification_setting_returns_zero_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingsService->updateNotificationSetting($user->id, 999, 1);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getNotificationSetting returns setting
     */
    public function test_get_notification_setting_returns_setting()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $setting = \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 5,
            'value' => 1,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->getNotificationSetting($user->id, 5);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\UserNotificationSettings::class, $result);
        $this->assertEquals($setting->id, $result->id);
        $this->assertEquals(1, $result->value);
    }

    /**
     * Test getNotificationSetting returns null when not found
     */
    public function test_get_notification_setting_returns_null_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingsService->getNotificationSetting($user->id, 999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getUserNotificationSettings returns all user settings
     */
    public function test_get_user_notification_settings_returns_all_settings()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserNotificationSettings::factory()->count(3)->create([
            'idUser' => $user->id,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->getUserNotificationSettings($user->id);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getUserNotificationSettings returns empty collection when no settings
     */
    public function test_get_user_notification_settings_returns_empty_when_no_settings()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingsService->getUserNotificationSettings($user->id);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test upsertNotificationSetting creates new setting
     */
    public function test_upsert_notification_setting_creates_new()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingsService->upsertNotificationSetting($user->id, 10, 1);

        // Assert
        $this->assertInstanceOf(\App\Models\UserNotificationSettings::class, $result);
        $this->assertEquals($user->id, $result->idUser);
        $this->assertEquals(10, $result->idNotification);
        $this->assertEquals(1, $result->value);
        $this->assertDatabaseHas('user_notification_setting', [
            'idUser' => $user->id,
            'idNotification' => 10,
            'value' => 1,
        ]);
    }

    /**
     * Test upsertNotificationSetting updates existing setting
     */
    public function test_upsert_notification_setting_updates_existing()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserNotificationSettings::factory()->create([
            'idUser' => $user->id,
            'idNotification' => 15,
            'value' => 0,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->upsertNotificationSetting($user->id, 15, 1);

        // Assert
        $this->assertInstanceOf(\App\Models\UserNotificationSettings::class, $result);
        $this->assertEquals(1, $result->value);
        $this->assertDatabaseHas('user_notification_setting', [
            'idUser' => $user->id,
            'idNotification' => 15,
            'value' => 1,
        ]);
    }

    /**
     * Test deleteUserNotificationSettings deletes all user settings
     */
    public function test_delete_user_notification_settings_deletes_all()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserNotificationSettings::factory()->count(3)->create([
            'idUser' => $user->id,
        ]);

        // Act
        $result = $this->userNotificationSettingsService->deleteUserNotificationSettings($user->id);

        // Assert
        $this->assertEquals(3, $result);
        $this->assertDatabaseMissing('user_notification_setting', [
            'idUser' => $user->id,
        ]);
    }

    /**
     * Test deleteUserNotificationSettings returns zero when no settings
     */
    public function test_delete_user_notification_settings_returns_zero_when_empty()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userNotificationSettingsService->deleteUserNotificationSettings($user->id);

        // Assert
        $this->assertEquals(0, $result);
    }
}
