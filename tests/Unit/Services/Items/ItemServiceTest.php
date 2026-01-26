<?php

namespace Tests\Unit\Services\Items;

use App\Models\Deal;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Platform;
use App\Models\User;
use App\Services\Items\ItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ItemService $itemService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->itemService = new ItemService();
    }

    /**
     * Test getting items without search
     */
    public function test_get_items_returns_paginated_results()
    {
        // Arrange
        Item::factory()->count(10)->create();

        // Act
        $result = $this->itemService->getItems(null, 5);

        // Assert
        $this->assertCount(5, $result->items());
        $this->assertEquals(10, $result->total());
    }

    /**
     * Test getting items with search
     */
    public function test_get_items_filters_by_search_term()
    {
        // Arrange
        Item::factory()->create(['name' => 'Product One']);
        Item::factory()->create(['name' => 'Product Two']);
        Item::factory()->create(['name' => 'Another Item']);

        // Act
        $result = $this->itemService->getItems('Product', 10);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /**
     * Test getting items by platform
     */
    public function test_get_items_by_platform_filters_correctly()
    {
        // Arrange
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        Item::factory()->count(3)->create(['platform_id' => $platform1->id]);
        Item::factory()->count(2)->create(['platform_id' => $platform2->id]);

        // Act
        $result = $this->itemService->getItemsByPlatform($platform1->id, null, 15);

        // Assert
        $this->assertEquals(3, $result->total());
    }

    /**
     * Test getting items by platform with search
     */
    public function test_get_items_by_platform_with_search()
    {
        // Arrange
        $platform = Platform::factory()->create();
        Item::factory()->create([
            'platform_id' => $platform->id,
            'name' => 'Laptop Computer'
        ]);
        Item::factory()->create([
            'platform_id' => $platform->id,
            'name' => 'Desktop Computer'
        ]);
        Item::factory()->create([
            'platform_id' => $platform->id,
            'name' => 'Phone Device'
        ]);

        // Act
        $result = $this->itemService->getItemsByPlatform($platform->id, 'Computer', 15);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /**
     * Test finding item by ID
     */
    public function test_find_item_returns_item_when_exists()
    {
        // Arrange
        $item = Item::factory()->create();

        // Act
        $result = $this->itemService->findItem($item->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Item::class, $result);
        $this->assertEquals($item->id, $result->id);
    }

    /**
     * Test finding item by ID when not exists
     */
    public function test_find_item_returns_null_when_not_exists()
    {
        // Act
        $result = $this->itemService->findItem(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test finding item or fail
     */
    public function test_find_item_or_fail_returns_item()
    {
        // Arrange
        $item = Item::factory()->create();

        // Act
        $result = $this->itemService->findItemOrFail($item->id);

        // Assert
        $this->assertInstanceOf(Item::class, $result);
        $this->assertEquals($item->id, $result->id);
    }

    /**
     * Test finding item or fail throws exception
     */
    public function test_find_item_or_fail_throws_exception()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->itemService->findItemOrFail(9999);
    }

    /**
     * Test creating an item
     */
    public function test_create_item_successfully_creates_item()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $data = [
            'name' => 'Test Item',
            'ref' => 'REF123',
            'platform_id' => $platform->id,
            'description' => 'Test Description',
        ];

        // Act
        $result = $this->itemService->createItem($data);

        // Assert
        $this->assertInstanceOf(Item::class, $result);
        $this->assertEquals('Test Item', $result->name);
        $this->assertDatabaseHas('items', ['name' => 'Test Item']);
    }

    /**
     * Test updating an item
     */
    public function test_update_item_successfully_updates()
    {
        // Arrange
        $item = Item::factory()->create(['name' => 'Old Name']);
        $updateData = ['name' => 'New Name'];

        // Act
        $result = $this->itemService->updateItem($item->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'New Name'
        ]);
    }

    /**
     * Test deleting an item
     */
    public function test_delete_item_successfully_deletes()
    {
        // Arrange
        $item = Item::factory()->create();

        // Act
        $result = $this->itemService->deleteItem($item->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    /**
     * Test getting items by deal
     */
    public function test_get_items_by_deal_returns_correct_items()
    {
        // Arrange
        $deal = Deal::factory()->create();
        Item::factory()->count(3)->create(['deal_id' => $deal->id]);
        Item::factory()->count(2)->create(); // Without deal

        // Act
        $result = $this->itemService->getItemsByDeal($deal->id);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getting items for a deal
     */
    public function test_get_items_for_deal_returns_deal_items()
    {
        // Arrange
        $deal = Deal::factory()->create();
        Item::factory()->count(4)->create(['deal_id' => $deal->id]);
        Item::factory()->count(2)->create(); // Different deal

        // Act
        $result = $this->itemService->getItemsForDeal($deal->id);

        // Assert
        $this->assertCount(4, $result);
    }

    /**
     * Test bulk update deal
     */
    public function test_bulk_update_deal_updates_items()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $items = Item::factory()->count(3)->create();
        $itemIds = $items->pluck('id')->toArray();

        // Act
        $result = $this->itemService->bulkUpdateDeal($itemIds, $deal->id);

        // Assert
        $this->assertEquals(3, $result);
        foreach ($itemIds as $itemId) {
            $this->assertDatabaseHas('items', [
                'id' => $itemId,
                'deal_id' => $deal->id
            ]);
        }
    }

    /**
     * Test bulk remove from deal
     */
    public function test_bulk_remove_from_deal_removes_items()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $items = Item::factory()->count(3)->create(['deal_id' => $deal->id]);
        $itemIds = $items->pluck('id')->toArray();

        // Act
        $result = $this->itemService->bulkRemoveFromDeal($itemIds, $deal->id);

        // Assert
        $this->assertEquals(3, $result);
        foreach ($itemIds as $itemId) {
            $this->assertDatabaseHas('items', [
                'id' => $itemId,
                'deal_id' => null
            ]);
        }
    }

    /**
     * Test finding item by ref and platform
     */
    public function test_find_by_ref_and_platform_returns_item()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $item = Item::factory()->create([
            'ref' => 'REF123',
            'platform_id' => $platform->id
        ]);

        // Act
        $result = $this->itemService->findByRefAndPlatform('REF123', $platform->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($item->id, $result->id);
    }

    /**
     * Test finding item by ref and platform returns null
     */
    public function test_find_by_ref_and_platform_returns_null_when_not_found()
    {
        // Arrange
        $platform = Platform::factory()->create();

        // Act
        $result = $this->itemService->findByRefAndPlatform('NOTFOUND', $platform->id);

        // Assert
        $this->assertNull($result);
    }
}
