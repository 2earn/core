<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class EventService
{
    /**
     * Get event by ID
     *
     * @param int $id
     * @return Event|null
     */
    public function getById(int $id): ?Event
    {
        try {
            return Event::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching event by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all enabled events ordered by newest first
     *
     * @return Collection
     */
    public function getEnabledEvents(): Collection
    {
        try {
            return Event::where('enabled', 1)->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching enabled events: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get all events
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return Event::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all events: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Create a new event
     *
     * @param array $data
     * @return Event|null
     */
    public function create(array $data): ?Event
    {
        try {
            return Event::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update an event
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $event = Event::findOrFail($id);
            return $event->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an event
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $event = Event::findOrFail($id);
            return $event->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find event by ID or fail
     *
     * @param int $id
     * @return Event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findByIdOrFail(int $id): Event
    {
        return Event::findOrFail($id);
    }

    /**
     * Get event with main image by ID
     *
     * @param int $id
     * @return Event|null
     */
    public function getWithMainImage(int $id): ?Event
    {
        try {
            return Event::with('mainImage')->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching event with main image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get paginated events with search and counts
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedWithCounts(?string $search = null, int $perPage = 10)
    {
        try {
            $query = Event::withCount(['comments', 'likes']);

            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            return $query->orderByDesc('published_at')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated events with counts: ' . $e->getMessage());
            return Event::paginate($perPage);
        }
    }

    /**
     * Get event with relationships (mainImage, likes, comments.user)
     *
     * @param int $id
     * @return Event|null
     */
    public function getWithRelationships(int $id): ?Event
    {
        try {
            return Event::with(['mainImage', 'likes', 'comments.user'])->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching event with relationships: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has liked an event
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function hasUserLiked(int $eventId, int $userId): bool
    {
        try {
            return Event::whereHas('likes', function ($q) use ($userId, $eventId) {
                $q->where('user_id', $userId)->where('likable_id', $eventId);
            })->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if user liked event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add like to an event
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function addLike(int $eventId, int $userId): bool
    {
        try {
            $event = Event::findOrFail($eventId);
            $event->likes()->create(['user_id' => $userId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding like to event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove like from an event
     *
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function removeLike(int $eventId, int $userId): bool
    {
        try {
            $event = Event::findOrFail($eventId);
            $event->likes()->where('user_id', $userId)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error removing like from event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add comment to an event
     *
     * @param int $eventId
     * @param int $userId
     * @param string $content
     * @return bool
     */
    public function addComment(int $eventId, int $userId, string $content): bool
    {
        try {
            $event = Event::findOrFail($eventId);
            $event->comments()->create([
                'user_id' => $userId,
                'content' => $content
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding comment to event: ' . $e->getMessage());
            return false;
        }
    }
}

