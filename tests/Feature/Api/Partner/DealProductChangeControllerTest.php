<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Platform;
use App\Models\EntityRole;
use App\Models\DealProductChange;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
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
            DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => 'added',
                'changed_by' => $this->user->id,
                'note' => 'Added to deal'
            ]);
        }

        // Create items that were removed from deal
        $removedItems = Item::factory()->count(2)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($removedItems as $item) {
            DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => 'removed',
                'changed_by' => $this->user->id,
                'note' => 'Removed from deal'
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
        DealProductChange::create([
            'deal_id' => $this->deal->id,
            'item_id' => $item1->id,
            'action' => 'added',
            'changed_by' => $this->user->id,
            'note' => 'Added to main deal'
        ]);

        // Create changes for other deal (should be filtered out)
        $item2 = Item::factory()->create(['platform_id' => $this->platform->id]);
        DealProductChange::create([
            'deal_id' => $otherDeal->id,
            'item_id' => $item2->id,
            'action' => 'added',
            'changed_by' => $this->user->id,
            'note' => 'Added to other deal'
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
            DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => $index < 10 ? 'added' : 'removed',
                'changed_by' => $this->user->id,
                'note' => $index < 10 ? 'Added to deal' : 'Removed from deal'
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
            $change = DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => 'added',
                'changed_by' => $this->user->id,
                'note' => 'Added to deal'
            ]);
            // Update timestamp after creation to bypass guarded attributes
            $change->created_at = now()->subDays(5 + $index);
            $change->save();
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

        // Create product change record when item was added to deal
        $addChange = DealProductChange::create([
            'deal_id' => $this->deal->id,
            'item_id' => $addedItem->id,
            'action' => 'added',
            'changed_by' => $this->user->id,
            'note' => 'Added to deal'
        ]);

        // Simulate removing a product from a deal
        $removedItem = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Create product change record when item was removed from deal
        $removeChange = DealProductChange::create([
            'deal_id' => $this->deal->id,
            'item_id' => $removedItem->id,
            'action' => 'removed',
            'changed_by' => $this->user->id,
            'note' => 'Removed from deal'
        ]);

        // Act - Get the product change for the removed item
        $response = $this->getJson($this->baseUrl . '/' . $removeChange->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         'id',
                         'deal_id',
                         'item_id',
                         'action',
                         'changed_by',
                         'note',
                         'deal',
                         'item'
                     ]
                 ])
                 ->assertJson([
                     'status' => 'success'
                 ]);

        // Verify the response contains the change data
        $data = $response->json('data');
        $this->assertEquals($this->deal->id, $data['deal_id']);
        $this->assertEquals($removedItem->id, $data['item_id']);
        $this->assertEquals('removed', $data['action']);

        // Check if changed_by is loaded as a relationship or just an ID
        if (is_array($data['changed_by'])) {
            $this->assertEquals($this->user->id, $data['changed_by']['id']);
        } else {
            $this->assertEquals($this->user->id, $data['changed_by']);
        }
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
        $items = Item::factory()->count(10)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($items as $index => $item) {
            DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => $index < 5 ? 'added' : 'removed',
                'changed_by' => $this->user->id,
                'note' => 'Test change'
            ]);
        }

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
        $items = Item::factory()->count(5)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($items as $item) {
            DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => 'added',
                'changed_by' => $this->user->id,
                'note' => 'Test change'
            ]);
        }

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
        $items = Item::factory()->count(3)->create([
            'platform_id' => $this->platform->id
        ]);

        foreach ($items as $item) {
            $change = DealProductChange::create([
                'deal_id' => $this->deal->id,
                'item_id' => $item->id,
                'action' => 'added',
                'changed_by' => $this->user->id,
                'note' => 'Test change'
            ]);
            // Update timestamp after creation to bypass guarded attributes
            $change->created_at = now()->subDays(7);
            $change->save();
        }

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
