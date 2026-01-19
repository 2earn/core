<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Platform;
use App\Models\EntityRole;
use App\Models\ItemDealHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DealProductChangeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $deal;
    protected $item;
    protected $baseUrl = '/api/partner/deals/product-changes';

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create([
            'name' => 'Test Partner User',
            'email' => 'partner@test.com',
        ]);

        // Create platform
        $this->platform = Platform::factory()->create([
            'created_by' => $this->user->id,
            'enabled' => true
        ]);

        // Create EntityRole to link user to platform
        EntityRole::create([
            'user_id' => $this->user->id,
            'roleable_type' => Platform::class,
            'roleable_id' => $this->platform->id,
            'name' => 'owner'
        ]);

        // Create deal
        $this->deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        // Create item
        $this->item = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Mock the check.url middleware
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List product changes
     * Should show both items added to and removed from deals
     */
    public function test_can_list_product_changes()
    {
        // Arrange - Create items that were added to deal (currently active)
        $activeItems = Item::factory()->count(3)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($activeItems as $item) {
            ItemDealHistory::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'start_date' => now()->subDays(rand(5, 20)),
                'end_date' => null, // Still in the deal
                'created_by' => $this->user->id
            ]);
        }

        // Create items that were removed from deal
        $removedItems = Item::factory()->count(2)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($removedItems as $item) {
            ItemDealHistory::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'start_date' => now()->subDays(30),
                'end_date' => now()->subDays(rand(1, 10)), // Removed from deal
                'created_by' => $this->user->id,
                'updated_by' => $this->user->id
            ]);
        }

        // Act
        $response = $this->getJson($this->baseUrl);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with filters
     * Filter by specific deal to see only changes for that deal
     */
    public function test_can_list_product_changes_with_filters()
    {
        // Arrange - Create another deal to test filtering
        $otherDeal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        // Create changes for the main deal
        $item1 = Item::factory()->create(['platform_id' => $this->platform->id]);
        ItemDealHistory::create([
            'deal_id' => $this->deal->id,
            'item_id' => $item1->id,
            'start_date' => now()->subDays(10),
            'end_date' => null, // Added to deal
            'created_by' => $this->user->id
        ]);

        // Create changes for other deal (should be filtered out)
        $item2 = Item::factory()->create(['platform_id' => $this->platform->id]);
        ItemDealHistory::create([
            'deal_id' => $otherDeal->id,
            'item_id' => $item2->id,
            'start_date' => now()->subDays(5),
            'end_date' => null,
            'created_by' => $this->user->id
        ]);

        // Act - Filter by specific deal
        $response = $this->getJson($this->baseUrl . '?deal_id=' . $this->deal->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with pagination
     * Test pagination of product changes showing both additions and removals
     */
    public function test_can_list_product_changes_with_pagination()
    {
        // Arrange - Create 20 product changes (mix of additions and removals)
        $items = Item::factory()->count(20)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($items as $index => $item) {
            ItemDealHistory::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'start_date' => now()->subDays(30 - $index),
                // Half still in deal, half removed
                'end_date' => $index < 10 ? null : now()->subDays(rand(1, 5)),
                'created_by' => $this->user->id,
                'updated_by' => $index < 10 ? null : $this->user->id
            ]);
        }

        // Act
        $response = $this->getJson($this->baseUrl . '?per_page=10');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with date filters
     * Test filtering product changes by date range
     */
    public function test_can_list_product_changes_with_date_filters()
    {
        // Arrange - Create changes at different dates
        $items = Item::factory()->count(3)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($items as $index => $item) {
            ItemDealHistory::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'start_date' => now()->subDays(5 + $index),
                'end_date' => null,
                'created_by' => $this->user->id
            ]);
        }

        // Act
        $fromDate = now()->subDays(10)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');
        $response = $this->getJson($this->baseUrl . "?from_date={$fromDate}&to_date={$toDate}");

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes/{id} - Show single product change
     * This test validates tracking of adding and removing products from deals
     */
    public function test_can_show_single_product_change()
    {
        // Arrange - Simulate adding a product to a deal
        $addedItem = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Create history record when item was added to deal
        $addHistory = ItemDealHistory::create([
            'deal_id' => $this->deal->id,
            'item_id' => $addedItem->id,
            'start_date' => now()->subDays(10), // Added 10 days ago
            'end_date' => null, // Still active in the deal
            'created_by' => $this->user->id
        ]);

        // Simulate removing a product from a deal
        $removedItem = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Create history record when item was removed from deal
        $removeHistory = ItemDealHistory::create([
            'deal_id' => $this->deal->id,
            'item_id' => $removedItem->id,
            'start_date' => now()->subDays(30), // Was added 30 days ago
            'end_date' => now()->subDays(5), // Removed 5 days ago
            'updated_by' => $this->user->id
        ]);

        // Act - Get the history of the removed item (showing add + remove action)
        $response = $this->getJson($this->baseUrl . '/' . $removeHistory->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);

        // Verify the response contains the history data
        $data = $response->json('data');
        $this->assertEquals($this->deal->id, $data['deal_id']);
        $this->assertEquals($removedItem->id, $data['item_id']);
        $this->assertNotNull($data['start_date']); // Has add date
        $this->assertNotNull($data['end_date']); // Has remove date
    }

    /**
     * Test: GET /api/partner/deals/product-changes/{id} - Show returns 404 for non-existent change
     */
    public function test_show_returns_404_for_non_existent_change()
    {
        // Act
        $response = $this->getJson($this->baseUrl . '/99999');

        // Assert
        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Product change not found'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes/statistics - Get statistics
     */
    public function test_can_get_statistics()
    {
        // Arrange
        ItemDealHistory::factory()->count(10)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/statistics');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes/statistics - Statistics with filters
     */
    public function test_can_get_statistics_with_filters()
    {
        // Arrange
        ItemDealHistory::factory()->count(5)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/statistics?deal_id=' . $this->deal->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes/statistics - Statistics with date range
     */
    public function test_can_get_statistics_with_date_range()
    {
        // Arrange
        ItemDealHistory::factory()->count(3)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
            'created_at' => now()->subDays(7)
        ]);

        // Act
        $fromDate = now()->subDays(30)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');
        $response = $this->getJson($this->baseUrl . "/statistics?from_date={$fromDate}&to_date={$toDate}");

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: Unauthorized access without valid IP
     */
    public function test_fails_without_valid_ip()
    {
        // Set invalid IP
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        // Act
        $response = $this->getJson($this->baseUrl);

        // Assert
        $response->assertStatus(403)
                 ->assertJson([
                     'error' => 'Unauthorized. Invalid IP.'
                 ]);
    }
}
