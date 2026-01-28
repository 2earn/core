<?php

namespace Tests\Unit\Services\Deals;

use App\Models\Deal;
use App\Models\DealProductChange;
use App\Models\Item;
use App\Models\User;
use App\Services\Deals\DealProductChangeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DealProductChangeServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected DealProductChangeService $dealProductChangeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dealProductChangeService = new DealProductChangeService();
    }

    /**
     * Test getFilteredChanges returns paginated results
     */
    public function test_get_filtered_changes_returns_paginated_results()
    {
        // Arrange
        DealProductChange::factory()->count(20)->create();

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges([], 10);

        // Assert
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual(20, $result->total());
    }

    /**
     * Test getFilteredChanges filters by deal_id
     */
    public function test_get_filtered_changes_filters_by_deal_id()
    {
        // Arrange
        $deal = Deal::factory()->create();
        DealProductChange::factory()->count(3)->create(['deal_id' => $deal->id]);
        DealProductChange::factory()->count(2)->create(); // Other deals

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges(['deal_id' => $deal->id]);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());

        foreach ($result->items() as $change) {
            $this->assertEquals($deal->id, $change->deal_id);
        }
    }

    /**
     * Test getFilteredChanges filters by action
     */
    public function test_get_filtered_changes_filters_by_action()
    {
        // Arrange
        DealProductChange::factory()->added()->count(3)->create();
        DealProductChange::factory()->removed()->count(2)->create();

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges(['action' => 'added']);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->total());

        foreach ($result->items() as $change) {
            $this->assertEquals('added', $change->action);
        }
    }

    /**
     * Test getFilteredChanges filters by date range
     */
    public function test_get_filtered_changes_filters_by_date_range()
    {
        // Arrange
        $oldChange = DealProductChange::factory()->create(['created_at' => now()->subDays(10)]);
        $recentChange = DealProductChange::factory()->create(['created_at' => now()]);

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges([
            'from_date' => now()->subDays(5),
            'to_date' => now()->addDay()
        ]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getChangeById returns change with relationships
     */
    public function test_get_change_by_id_returns_change()
    {
        // Arrange
        $change = DealProductChange::factory()->create();

        // Act
        $result = $this->dealProductChangeService->getChangeById($change->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($change->id, $result->id);
        $this->assertNotNull($result->deal);
        $this->assertNotNull($result->item);
    }

    /**
     * Test getChangeById returns null for non-existent
     */
    public function test_get_change_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->dealProductChangeService->getChangeById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getStatistics returns statistics array
     */
    public function test_get_statistics_returns_statistics()
    {
        // Arrange
        $deal = Deal::factory()->create();
        DealProductChange::factory()->added()->count(5)->create(['deal_id' => $deal->id]);
        DealProductChange::factory()->removed()->count(3)->create(['deal_id' => $deal->id]);

        // Act
        $result = $this->dealProductChangeService->getStatistics(['deal_id' => $deal->id]);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_changes', $result);
        $this->assertArrayHasKey('added_count', $result);
        $this->assertArrayHasKey('removed_count', $result);
        $this->assertArrayHasKey('recent_changes', $result);
        $this->assertArrayHasKey('top_users', $result);

        $this->assertGreaterThanOrEqual(8, $result['total_changes']);
        $this->assertGreaterThanOrEqual(5, $result['added_count']);
        $this->assertGreaterThanOrEqual(3, $result['removed_count']);
    }

    /**
     * Test getStatistics returns recent changes
     */
    public function test_get_statistics_returns_recent_changes()
    {
        // Arrange
        DealProductChange::factory()->count(15)->create();

        // Act
        $result = $this->dealProductChangeService->getStatistics([]);

        // Assert
        $this->assertLessThanOrEqual(10, $result['recent_changes']->count());
    }

    /**
     * Test getStatistics returns top users
     */
    public function test_get_statistics_returns_top_users()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        DealProductChange::factory()->count(5)->create(['changed_by' => $user1->id]);
        DealProductChange::factory()->count(3)->create(['changed_by' => $user2->id]);

        // Act
        $result = $this->dealProductChangeService->getStatistics([]);

        // Assert
        $this->assertLessThanOrEqual(5, $result['top_users']->count());
    }

    /**
     * Test createChange creates a change record
     */
    public function test_create_change_creates_change()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $item = Item::factory()->create();
        $user = User::factory()->create();

        $data = [
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'added',
            'changed_by' => $user->id,
            'note' => 'Test note',
        ];

        // Act
        $result = $this->dealProductChangeService->createChange($data);

        // Assert
        $this->assertInstanceOf(DealProductChange::class, $result);
        $this->assertEquals($deal->id, $result->deal_id);
        $this->assertEquals($item->id, $result->item_id);
        $this->assertEquals('added', $result->action);
        $this->assertEquals('Test note', $result->note);

        $this->assertDatabaseHas('deal_product_changes', [
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'added'
        ]);
    }

    /**
     * Test createBulkChanges creates multiple changes
     */
    public function test_create_bulk_changes_creates_multiple_changes()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $items = Item::factory()->count(5)->create();
        $user = User::factory()->create();
        $itemIds = $items->pluck('id')->toArray();

        // Act
        $result = $this->dealProductChangeService->createBulkChanges(
            $deal->id,
            $itemIds,
            'added',
            $user->id,
            'Bulk add note'
        );

        // Assert
        $this->assertEquals(5, $result);

        foreach ($itemIds as $itemId) {
            $this->assertDatabaseHas('deal_product_changes', [
                'deal_id' => $deal->id,
                'item_id' => $itemId,
                'action' => 'added',
                'changed_by' => $user->id
            ]);
        }
    }

    /**
     * Test createBulkChanges without user
     */
    public function test_create_bulk_changes_without_user()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $items = Item::factory()->count(3)->create();
        $itemIds = $items->pluck('id')->toArray();

        // Act
        $result = $this->dealProductChangeService->createBulkChanges(
            $deal->id,
            $itemIds,
            'removed',
            null,
            null
        );

        // Assert
        $this->assertEquals(3, $result);

        foreach ($itemIds as $itemId) {
            $this->assertDatabaseHas('deal_product_changes', [
                'deal_id' => $deal->id,
                'item_id' => $itemId,
                'action' => 'removed',
                'changed_by' => null
            ]);
        }
    }

    /**
     * Test getFilteredChanges orders by created_at desc
     */
    public function test_get_filtered_changes_orders_by_created_at_desc()
    {
        // Arrange
        $change1 = DealProductChange::factory()->create(['created_at' => now()->subDays(2)]);
        $change2 = DealProductChange::factory()->create(['created_at' => now()]);

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges([]);

        // Assert
        $this->assertEquals($change2->id, $result->items()[0]->id);
    }

    /**
     * Test getFilteredChanges filters by multiple criteria
     */
    public function test_get_filtered_changes_filters_by_multiple_criteria()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $item = Item::factory()->create();
        $user = User::factory()->create();

        DealProductChange::factory()->create([
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'added',
            'changed_by' => $user->id
        ]);

        DealProductChange::factory()->count(5)->create(); // Other changes

        // Act
        $result = $this->dealProductChangeService->getFilteredChanges([
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'added',
            'changed_by' => $user->id
        ]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());

        $change = $result->items()[0];
        $this->assertEquals($deal->id, $change->deal_id);
        $this->assertEquals($item->id, $change->item_id);
        $this->assertEquals('added', $change->action);
        $this->assertEquals($user->id, $change->changed_by);
    }
}
