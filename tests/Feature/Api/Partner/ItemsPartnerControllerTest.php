<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

    public function test_create_item_fails_with_invalid_data()
    {
        $invalidData = ['created_by' => $this->user->id];

        $response = $this->postJson($this->baseUrl, $invalidData);

        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/deal/' . $this->deal->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
