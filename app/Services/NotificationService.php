<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Get paginated notifications for a user with optional filtering
     *
     * @param int $userId
     * @param string $filter 'all', 'read', or 'unread'
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedNotifications(int $userId, string $filter = 'all', int $perPage = 10): LengthAwarePaginator
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $query = $user->notifications()->latest();

            if ($filter === 'unread') {
                $query->whereNull('read_at');
            } elseif ($filter === 'read') {
                $query->whereNotNull('read_at');
            }

            return $query->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated notifications: ' . $e->getMessage(), [
                'userId' => $userId,
                'filter' => $filter
            ]);
            // Return empty paginator on error
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Mark a specific notification as read
     *
     * @param int $userId
     * @param string $notificationId
     * @return bool
     */
    public function markAsRead(int $userId, string $notificationId): bool
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $notification = $user->notifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage(), [
                'userId' => $userId,
                'notificationId' => $notificationId
            ]);
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param int $userId
     * @return bool
     */
    public function markAllAsRead(int $userId): bool
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $notifications = $user->notifications()->get();

            if ($notifications && $notifications->count() > 0) {
                $notifications->markAsRead();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage(), [
                'userId' => $userId
            ]);
            return false;
        }
    }

    /**
     * Get unread notification count for a user
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            return $user->unreadNotifications()->count();
        } catch (\Exception $e) {
            Log::error('Error getting unread notification count: ' . $e->getMessage(), [
                'userId' => $userId
            ]);
            return 0;
        }
    }

    /**
     * Delete a notification
     *
     * @param int $userId
     * @param string $notificationId
     * @return bool
     */
    public function deleteNotification(int $userId, string $notificationId): bool
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $notification = $user->notifications()->find($notificationId);

            if ($notification) {
                $notification->delete();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage(), [
                'userId' => $userId,
                'notificationId' => $notificationId
            ]);
            return false;
        }
    }

    /**
     * Get all notifications for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getAllNotifications(int $userId): Collection
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            return $user->notifications()->latest()->get();
        } catch (\Exception $e) {
            Log::error('Error getting all notifications: ' . $e->getMessage(), [
                'userId' => $userId
            ]);
            return new Collection();
        }
    }

    /**
     * Get notification history with advanced filtering
     *
     * @param array $filters Array of filter criteria
     * @return Collection
     */
    public function getNotificationHistory(array $filters = []): Collection
    {
        try {
            // Get history from settings manager
            $notifications = app(\App\Services\settingsManager::class)->getHistory();

            // Apply search filter
            if (!empty($filters['search'])) {
                $searchTerm = strtolower($filters['search']);
                $notifications = $notifications->filter(function ($item) use ($searchTerm) {
                    return str_contains(strtolower($item->reference ?? ''), $searchTerm)
                        || str_contains(strtolower($item->send ?? ''), $searchTerm)
                        || str_contains(strtolower($item->receiver ?? ''), $searchTerm)
                        || str_contains(strtolower($item->action ?? ''), $searchTerm)
                        || str_contains(strtolower($item->type ?? ''), $searchTerm)
                        || str_contains(strtolower($item->responce ?? ''), $searchTerm);
                });
            }

            // Apply individual field filters
            $fieldFilters = [
                'filterReference' => 'reference',
                'filterSource' => 'send',
                'filterReceiver' => 'receiver',
                'filterActions' => 'action',
                'filterDate' => 'date',
                'filterType' => 'type',
                'filterResponse' => 'responce'
            ];

            foreach ($fieldFilters as $filterKey => $fieldName) {
                if (!empty($filters[$filterKey])) {
                    $filterValue = strtolower($filters[$filterKey]);
                    $notifications = $notifications->filter(function ($item) use ($fieldName, $filterValue) {
                        return str_contains(strtolower($item->$fieldName ?? ''), $filterValue);
                    });
                }
            }

            return $notifications;
        } catch (\Exception $e) {
            Log::error('Error getting notification history: ' . $e->getMessage(), [
                'filters' => $filters
            ]);
            return new Collection();
        }
    }

    /**
     * Get paginated notification history with filtering
     *
     * @param array $filters Array of filter criteria
     * @param int $perPage Items per page
     * @param int $currentPage Current page number
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedHistory(array $filters = [], int $perPage = 10, int $currentPage = 1): \Illuminate\Pagination\LengthAwarePaginator
    {
        try {
            $notifications = $this->getNotificationHistory($filters);

            $total = $notifications->count();
            $items = $notifications->slice(($currentPage - 1) * $perPage, $perPage)->values();

            return new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'pageName' => 'page',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error getting paginated notification history: ' . $e->getMessage(), [
                'filters' => $filters
            ]);
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
    }
}
