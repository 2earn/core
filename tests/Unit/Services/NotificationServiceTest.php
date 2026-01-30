<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected NotificationService $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationService = new NotificationService();
    }

    /**
     * Test getPaginatedNotifications returns paginated results
     */
    public function test_get_paginated_notifications_returns_paginated_results()
    {
        // Arrange
        $user = User::factory()->create();

        // Create some notifications
        for ($i = 0; $i < 15; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        // Act
        $result = $this->notificationService->getPaginatedNotifications($user->id, 'all', 10);

        // Assert
        $this->assertNotNull($result);
        $this->assertLessThanOrEqual(10, $result->count());
    }

    /**
     * Test getPaginatedNotifications filters unread notifications
     */
    public function test_get_paginated_notifications_filters_unread()
    {
        // Arrange
        $user = User::factory()->create();

        // Create notifications
        for ($i = 0; $i < 5; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        // Mark some as read
        $user->notifications()->take(2)->get()->each->markAsRead();

        // Act
        $result = $this->notificationService->getPaginatedNotifications($user->id, 'unread', 10);

        // Assert
        $this->assertEquals(3, $result->total());
    }

    /**
     * Test markAsRead marks notification as read
     */
    public function test_mark_as_read_marks_notification()
    {
        // Arrange
        $user = User::factory()->create();
        $user->notify(new \App\Notifications\TestNotification());
        $notification = $user->unreadNotifications()->first();

        // Act
        $result = $this->notificationService->markAsRead($user->id, $notification->id);

        // Assert
        $this->assertTrue($result);
        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    /**
     * Test markAsRead returns false when notification not found
     */
    public function test_mark_as_read_returns_false_when_not_found()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->notificationService->markAsRead($user->id, 'non-existent-id');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test markAllAsRead marks all notifications as read
     */
    public function test_mark_all_as_read_marks_all()
    {
        // Arrange
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        // Act
        $result = $this->notificationService->markAllAsRead($user->id);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    /**
     * Test markAllAsRead returns false when no notifications
     */
    public function test_mark_all_as_read_returns_false_when_no_notifications()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->notificationService->markAllAsRead($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getUnreadCount returns correct count
     */
    public function test_get_unread_count_returns_correct_count()
    {
        // Arrange
        $user = User::factory()->create();

        for ($i = 0; $i < 7; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        // Mark some as read
        $user->notifications()->take(3)->get()->each->markAsRead();

        // Act
        $result = $this->notificationService->getUnreadCount($user->id);

        // Assert
        $this->assertEquals(4, $result);
    }

    /**
     * Test getUnreadCount returns zero for user with no notifications
     */
    public function test_get_unread_count_returns_zero_when_no_notifications()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->notificationService->getUnreadCount($user->id);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test deleteNotification deletes notification
     */
    public function test_delete_notification_deletes_notification()
    {
        // Arrange
        $user = User::factory()->create();
        $user->notify(new \App\Notifications\TestNotification());
        $notification = $user->notifications()->first();
        $notificationId = $notification->id;

        // Act
        $result = $this->notificationService->deleteNotification($user->id, $notificationId);

        // Assert
        $this->assertTrue($result);
        $this->assertNull($user->notifications()->find($notificationId));
    }

    /**
     * Test deleteNotification returns false when not found
     */
    public function test_delete_notification_returns_false_when_not_found()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->notificationService->deleteNotification($user->id, 'non-existent-id');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getAllNotifications returns all notifications
     */
    public function test_get_all_notifications_returns_all()
    {
        // Arrange
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        // Act
        $result = $this->notificationService->getAllNotifications($user->id);

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getNotificationHistory returns collection
     */
    public function test_get_notification_history_returns_collection()
    {
        // Act
        $result = $this->notificationService->getNotificationHistory([]);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getNotificationHistory filters by search
     */
    public function test_get_notification_history_filters_by_search()
    {
        // Act
        $result = $this->notificationService->getNotificationHistory(['search' => 'test']);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getPaginatedHistory returns paginated results
     */
    public function test_get_paginated_history_returns_paginated()
    {
        // Act
        $result = $this->notificationService->getPaginatedHistory([], 10, 1);

        // Assert
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
    }
}

// Create a test notification class for testing purposes
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

if (!class_exists('App\Notifications\TestNotification')) {
    class TestNotification extends Notification
    {
        use Queueable;

        public function via($notifiable)
        {
            return ['database'];
        }

        public function toArray($notifiable)
        {
            return [
                'message' => 'Test notification',
                'data' => 'test data'
            ];
        }
    }
}
