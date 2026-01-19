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
     */
    public function test_can_list_product_changes()
    {
        // Arrange
        ItemDealHistory::factory()->count(5)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with filters
     */
    public function test_can_list_product_changes_with_filters()
    {
        // Arrange
        ItemDealHistory::factory()->count(3)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '?deal_id=' . $this->deal->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with pagination
     */
    public function test_can_list_product_changes_with_pagination()
    {
        // Arrange
        ItemDealHistory::factory()->count(20)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '?per_page=10');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes - List with date filters
     */
    public function test_can_list_product_changes_with_date_filters()
    {
        // Arrange
        ItemDealHistory::factory()->count(3)->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
            'created_at' => now()->subDays(5)
        ]);

        // Act
        $fromDate = now()->subDays(10)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');
        $response = $this->getJson($this->baseUrl . "?from_date={$fromDate}&to_date={$toDate}");

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/product-changes/{id} - Show single product change
     */
    public function test_can_show_single_product_change()
    {
        // Arrange
        $change = ItemDealHistory::factory()->create([
            'deal_id' => $this->deal->id,
            'item_id' => $this->item->id,
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/' . $change->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
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
