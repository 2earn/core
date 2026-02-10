<?php

namespace Tests\Feature\Api\v2;

use App\Models\Item;
use App\Models\Deal;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for ItemController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\ItemController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('items')]
class ItemControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_get_paginated_items()
    {
        Item::factory()->count(10)->create();

        $response = $this->getJson('/api/v2/items?per_page=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_items()
    {
        Item::factory()->create(['name' => 'Test Item']);

        $response = $this->getJson('/api/v2/items?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_items_for_platform()
    {
        $platform = Platform::factory()->create();
        Item::factory()->count(3)->create(['platform_id' => $platform->id]);

        $response = $this->getJson("/api/v2/items/platforms/{$platform->id}?per_page=15");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_get_items_by_deal()
    {
        $deal = Deal::factory()->create();

        $response = $this->getJson("/api/v2/items/deal?deal_id={$deal->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_item_by_id()
    {
        $item = Item::factory()->create();

        $response = $this->getJson("/api/v2/items/{$item->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_item()
    {
        $response = $this->getJson('/api/v2/items/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_create_item()
    {
        $platform = Platform::factory()->create();

        $data = [
            'name' => 'Test Item',
            'platform_id' => $platform->id,
            'price' => 100
        ];

        $response = $this->postJson('/api/v2/items', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_item_creation()
    {
        $response = $this->postJson('/api/v2/items', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'platform_id']);
    }

    #[Test]
    public function it_can_update_item()
    {
        $item = Item::factory()->create();

        $data = [
            'name' => 'Updated Item',
            'price' => 200
        ];

        $response = $this->putJson("/api/v2/items/{$item->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_delete_item()
    {
        $item = Item::factory()->create();

        $response = $this->deleteJson("/api/v2/items/{$item->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/items?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

