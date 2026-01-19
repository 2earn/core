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
        $this->deal = Deal::factory()->create(['platform_id' => $this->platform->id]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_create_item()
    {
        $itemData = [
            'name' => 'Test Item',
            'description' => 'Test description',
            'price' => 50.00,
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl, $itemData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_update_item()
    {
        $item = Item::factory()->create();

        $updateData = [
            'name' => 'Updated Item',
            'user_id' => $this->user->id
        ];

        $response = $this->putJson($this->baseUrl . '/' . $item->id, $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_list_items_for_deal()
    {
        Item::factory()->count(5)->create([
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/deal/' . $this->deal->id . '?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_add_items_to_deal_in_bulk()
    {
        $items = Item::factory()->count(3)->create();

        $bulkData = [
            'deal_id' => $this->deal->id,
            'item_ids' => $items->pluck('id')->toArray(),
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl . '/deal/add-bulk', $bulkData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_can_remove_items_from_deal_in_bulk()
    {
        $items = Item::factory()->count(3)->create([
            'deal_id' => $this->deal->id
        ]);

        $bulkData = [
            'deal_id' => $this->deal->id,
            'item_ids' => $items->pluck('id')->toArray(),
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->baseUrl . '/deal/remove-bulk', $bulkData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_create_item_fails_with_invalid_data()
    {
        $invalidData = ['user_id' => $this->user->id];

        $response = $this->postJson($this->baseUrl, $invalidData);

        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/deal/' . $this->deal->id . '?user_id=' . $this->user->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
