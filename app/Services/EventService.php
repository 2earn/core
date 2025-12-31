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
}

