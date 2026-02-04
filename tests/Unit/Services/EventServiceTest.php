<?php

namespace Tests\Unit\Services;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class EventServiceTest extends TestCase
{

    protected EventService $eventService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventService = new EventService();
    }

    /**
     * Test getting event by ID
     */
    public function test_get_by_id_returns_event_when_exists()
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $result = $this->eventService->getById($event->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Event::class, $result);
        $this->assertEquals($event->id, $result->id);
    }

    /**
     * Test getting event by ID when not exists
     */
    public function test_get_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->eventService->getById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getting enabled events
     */
    public function test_get_enabled_events_returns_only_enabled()
    {
        // Arrange
        Event::factory()->count(3)->create(['enabled' => 1]);
        Event::factory()->count(2)->create(['enabled' => 0]);

        // Act
        $result = $this->eventService->getEnabledEvents();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        foreach ($result as $event) {
            $this->assertEquals(1, $event->enabled);
        }
    }

    /**
     * Test getting all events
     */
    public function test_get_all_returns_all_events()
    {
        // Arrange
        Event::factory()->count(5)->create();

        // Act
        $result = $this->eventService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual(5, $result->count());
    }

    /**
     * Test creating an event
     */
    public function test_create_successfully_creates_event()
    {
        // Arrange
        $data = [
            'title' => 'New Event',
            'description' => 'Event Description',
            'enabled' => 1,
            'published_at' => now()
        ];

        // Act
        $result = $this->eventService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Event::class, $result);
        $this->assertEquals('New Event', $result->title);
        $this->assertDatabaseHas('events', ['title' => 'New Event']);
    }

    /**
     * Test updating an event
     */
    public function test_update_successfully_updates_event()
    {
        // Arrange
        $event = Event::factory()->create(['title' => 'Original Title']);
        $updateData = ['title' => 'Updated Title'];

        // Act
        $result = $this->eventService->update($event->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title'
        ]);
    }

    /**
     * Test updating non-existent event
     */
    public function test_update_returns_false_when_event_not_found()
    {
        // Act
        $result = $this->eventService->update(9999, ['title' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleting an event
     */
    public function test_delete_successfully_deletes_event()
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $result = $this->eventService->delete($event->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /**
     * Test deleting non-existent event
     */
    public function test_delete_returns_false_when_event_not_found()
    {
        // Act
        $result = $this->eventService->delete(9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test finding event by ID or fail
     */
    public function test_find_by_id_or_fail_returns_event()
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $result = $this->eventService->findByIdOrFail($event->id);

        // Assert
        $this->assertInstanceOf(Event::class, $result);
        $this->assertEquals($event->id, $result->id);
    }

    /**
     * Test finding event by ID or fail throws exception
     */
    public function test_find_by_id_or_fail_throws_exception_when_not_exists()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->eventService->findByIdOrFail(9999);
    }

    /**
     * Test getting event with main image
     */
    public function test_get_with_main_image_returns_event_with_relationship()
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $result = $this->eventService->getWithMainImage($event->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('mainImage'));
    }

    /**
     * Test getting event with relationships
     */
    public function test_get_with_relationships_loads_all_relationships()
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $result = $this->eventService->getWithRelationships($event->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('mainImage'));
        $this->assertTrue($result->relationLoaded('likes'));
        $this->assertTrue($result->relationLoaded('comments'));
    }
}
