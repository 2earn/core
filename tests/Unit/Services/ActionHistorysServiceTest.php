<?php

namespace Tests\Unit\Services;

use App\Models\action_historys;
use App\Services\ActionHistorysService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ActionHistorysServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ActionHistorysService $actionHistorysService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actionHistorysService = new ActionHistorysService();
    }

    /**
     * Test getById returns action history
     */
    public function test_get_by_id_returns_action_history()
    {
        // Arrange
        $actionHistory = action_historys::factory()->create();

        // Act
        $result = $this->actionHistorysService->getById($actionHistory->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($actionHistory->id, $result->id);
    }

    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->actionHistorysService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        action_historys::factory()->count(15)->create();

        // Act
        $result = $this->actionHistorysService->getPaginated(null, 10);

        // Assert
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual(15, $result->total());
    }

    /**
     * Test getPaginated with search filters by title
     */
    public function test_get_paginated_filters_by_search()
    {
        // Arrange
        action_historys::factory()->create(['title' => 'Unique Test Title']);
        action_historys::factory()->count(5)->create();

        // Act
        $result = $this->actionHistorysService->getPaginated('Unique Test');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getAll returns all action histories
     */
    public function test_get_all_returns_all_action_histories()
    {
        // Arrange
        $initialCount = action_historys::count();
        action_historys::factory()->count(5)->create();

        // Act
        $result = $this->actionHistorysService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 5, $result->count());
    }

    /**
     * Test update updates action history
     */
    public function test_update_updates_action_history()
    {
        // Arrange
        $actionHistory = action_historys::factory()->create(['title' => 'Old Title']);
        $data = ['title' => 'New Title'];

        // Act
        $result = $this->actionHistorysService->update($actionHistory->id, $data);

        // Assert
        $this->assertTrue($result);

        $actionHistory->refresh();
        $this->assertEquals('New Title', $actionHistory->title);
    }

    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->actionHistorysService->update(99999, ['title' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test update with multiple fields
     */
    public function test_update_with_multiple_fields()
    {
        // Arrange
        $actionHistory = action_historys::factory()->create();
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        // Act
        $result = $this->actionHistorysService->update($actionHistory->id, $data);

        // Assert
        $this->assertTrue($result);

        $actionHistory->refresh();
        $this->assertEquals('Updated Title', $actionHistory->title);
    }
}
