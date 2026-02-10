<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
class ItemsPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $deal;
    protected $baseUrl = '/api/partner/items';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_create_item()
    {
        $itemData = [
            'name' => 'Test Item',
            'ref' => 'TEST-' . uniqid(),
            'price' => 50.00,
            'description' => 'Test description',
            'platform_id' => $this->platform->id,
            'created_by' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl, $itemData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_update_item()
    {
        $item = Item::factory()->create(['platform_id' => $this->platform->id]);

        $updateData = [
            'name' => 'Updated Item',
            'updated_by' => $this->user->id
        ];

        $response = $this->putJson($this->baseUrl . '/' . $item->id, $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_show_item_detail()
    {
        $item = Item::factory()->create([
            'platform_id' => $this->platform->id,
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/' . $item->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'ref',
                         'price',
                         'discount',
                         'discount_2earn',
                         'photo_link',
                         'description',
                         'stock',
                         'platform_id',
                         'platform_name',
                         'is_assigned_to_deal',
                         'deal',
                         'created_at',
                         'updated_at'
                     ]
                 ])
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Item retrieved successfully'
                 ]);

        $this->assertEquals($item->id, $response->json('data.id'));
        $this->assertEquals($item->name, $response->json('data.name'));
        $this->assertTrue($response->json('data.is_assigned_to_deal'));
    }

    public function test_show_item_detail_not_found()
    {
        $response = $this->getJson($this->baseUrl . '/99999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Item not found'
                 ]);
    }

    public function test_can_list_items_for_deal()
    {
        // Create validated deal
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        Item::factory()->count(5)->create([
            'deal_id' => $deal->id,
            'platform_id' => $this->platform->id
        ]);

        $response = $this->getJson($this->baseUrl . '/deal/' . $deal->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['deal_id', 'deal_name', 'products_count', 'products']);
    }

    public function test_can_add_items_to_deal_in_bulk()
    {
        $items = Item::factory()->count(3)->create(['platform_id' => $this->platform->id]);

        $bulkData = [
            'deal_id' => $this->deal->id,
            'product_ids' => $items->pluck('id')->toArray(),
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl . '/deal/add-bulk', $bulkData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_can_remove_items_from_deal_in_bulk()
    {
        $items = Item::factory()->count(3)->create([
            'deal_id' => $this->deal->id,
            'platform_id' => $this->platform->id
        ]);

        $bulkData = [
            'deal_id' => $this->deal->id,
            'product_ids' => $items->pluck('id')->toArray(),
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl . '/deal/remove-bulk', $bulkData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_can_list_items_with_platform_filter()
    {
        // Create items for this platform - some with deals, some without
        Item::factory()->count(3)->create([
            'platform_id' => $this->platform->id,
            'deal_id' => $this->deal->id
        ]);

        Item::factory()->count(2)->create([
            'platform_id' => $this->platform->id,
            'deal_id' => null
        ]);

        // Create items for another platform (should not be returned)
        $otherPlatform = Platform::factory()->create();
        Item::factory()->count(2)->create([
            'platform_id' => $otherPlatform->id
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'platform_id',
                         'items',
                         'pagination' => [
                             'current_page',
                             'per_page',
                             'total',
                             'last_page',
                             'from',
                             'to'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Items retrieved successfully',
                     'data' => [
                         'platform_id' => $this->platform->id
                     ]
                 ]);

        $responseData = $response->json('data');
        $this->assertCount(5, $responseData['items']);
        $this->assertEquals(5, $responseData['pagination']['total']);

        // Check that is_assigned_to_deal field exists
        foreach ($responseData['items'] as $item) {
            $this->assertArrayHasKey('is_assigned_to_deal', $item);
            $this->assertArrayHasKey('deal', $item);
        }
    }

    public function test_can_list_items_with_pagination()
    {
        Item::factory()->count(25)->create([
            'platform_id' => $this->platform->id
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id . '&per_page=10&page=1');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'items',
                         'pagination'
                     ]
                 ]);

        $responseData = $response->json('data');
        $this->assertEquals(10, count($responseData['items']));
        $this->assertEquals(1, $responseData['pagination']['current_page']);
        $this->assertEquals(10, $responseData['pagination']['per_page']);
        $this->assertEquals(25, $responseData['pagination']['total']);
    }

    public function test_list_items_shows_deal_assignment_status()
    {
        // Create item with deal
        $itemWithDeal = Item::factory()->create([
            'platform_id' => $this->platform->id,
            'deal_id' => $this->deal->id
        ]);

        // Create item without deal
        $itemWithoutDeal = Item::factory()->create([
            'platform_id' => $this->platform->id,
            'deal_id' => null
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id);

        $response->assertStatus(200);

        $items = $response->json('data.items');

        // Find our items in the response
        $itemWithDealData = collect($items)->firstWhere('id', $itemWithDeal->id);
        $itemWithoutDealData = collect($items)->firstWhere('id', $itemWithoutDeal->id);

        // Verify is_assigned_to_deal is true for item with deal
        $this->assertTrue($itemWithDealData['is_assigned_to_deal']);
        $this->assertNotNull($itemWithDealData['deal']);
        $this->assertEquals($this->deal->id, $itemWithDealData['deal']['id']);

        // Verify is_assigned_to_deal is false for item without deal
        $this->assertFalse($itemWithoutDealData['is_assigned_to_deal']);
        $this->assertNull($itemWithoutDealData['deal']);
    }

    public function test_list_items_fails_without_platform_id()
    {
        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_list_items_fails_with_invalid_platform_id()
    {
        $response = $this->getJson($this->baseUrl . '?platform_id=99999');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_search_items_by_name()
    {
        // Create items with different names
        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Red Shirt',
            'ref' => 'RED-001'
        ]);

        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Blue Pants',
            'ref' => 'BLUE-001'
        ]);

        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Red Hat',
            'ref' => 'RED-002'
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id . '&search=Red');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'data' => [
                         'search' => 'Red'
                     ]
                 ]);

        $items = $response->json('data.items');
        $this->assertCount(2, $items);

        foreach ($items as $item) {
            $this->assertStringContainsStringIgnoringCase('Red', $item['name']);
        }
    }

    public function test_can_search_items_by_ref()
    {
        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Product A',
            'ref' => 'SKU-123'
        ]);

        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Product B',
            'ref' => 'SKU-456'
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id . '&search=SKU-123');

        $response->assertStatus(200);

        $items = $response->json('data.items');
        $this->assertCount(1, $items);
        $this->assertEquals('SKU-123', $items[0]['ref']);
    }

    public function test_can_search_items_by_description()
    {
        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Product A',
            'description' => 'This is a premium quality product'
        ]);

        Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Product B',
            'description' => 'Standard quality item'
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id . '&search=premium');

        $response->assertStatus(200);

        $items = $response->json('data.items');
        $this->assertCount(1, $items);
        $this->assertStringContainsStringIgnoringCase('premium', $items[0]['description']);
    }

    public function test_search_returns_empty_when_no_matches()
    {
        Item::factory()->count(3)->create([
            'platform_id' => $this->platform->id,
            'name' => 'Test Product'
        ]);

        $response = $this->getJson($this->baseUrl . '?platform_id=' . $this->platform->id . '&search=NonexistentProduct');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'data' => [
                         'search' => 'NonexistentProduct'
                     ]
                 ]);

        $items = $response->json('data.items');
        $this->assertCount(0, $items);
        $this->assertEquals(0, $response->json('data.pagination.total'));
    }

    public function test_create_item_fails_with_invalid_data()
    {
        $invalidData = ['created_by' => $this->user->id];

        $response = $this->postJson($this->baseUrl, $invalidData);

        $response->assertStatus(422);
    }

    public function test_can_remove_platform_from_item()
    {
        $item = Item::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Test Item with Platform'
        ]);

        $this->assertNotNull($item->platform_id);
        $this->assertEquals($this->platform->id, $item->platform_id);

        $response = $this->deleteJson($this->baseUrl . '/' . $item->id . '/platform', [
            'updated_by' => $this->user->id
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'ref',
                         'platform_id',
                         'old_platform_id'
                     ]
                 ])
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Platform removed from item successfully',
                     'data' => [
                         'id' => $item->id,
                         'platform_id' => null,
                         'old_platform_id' => $this->platform->id
                     ]
                 ]);

        // Verify in database that platform_id is now null
        $item->refresh();
        $this->assertNull($item->platform_id);
    }

    public function test_remove_platform_from_item_not_found()
    {
        $response = $this->deleteJson($this->baseUrl . '/99999/platform', [
            'updated_by' => $this->user->id
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Item not found'
                 ]);
    }

    public function test_remove_platform_from_item_fails_without_updated_by()
    {
        $item = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        $response = $this->deleteJson($this->baseUrl . '/' . $item->id . '/platform', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_remove_platform_from_item_with_invalid_user()
    {
        $item = Item::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        $response = $this->deleteJson($this->baseUrl . '/' . $item->id . '/platform', [
            'updated_by' => 99999
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_can_remove_platform_from_item_already_without_platform()
    {
        $item = Item::factory()->create([
            'platform_id' => null
        ]);

        $this->assertNull($item->platform_id);

        $response = $this->deleteJson($this->baseUrl . '/' . $item->id . '/platform', [
            'updated_by' => $this->user->id
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Platform removed from item successfully',
                     'data' => [
                         'platform_id' => null,
                         'old_platform_id' => null
                     ]
                 ]);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/deal/' . $this->deal->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
