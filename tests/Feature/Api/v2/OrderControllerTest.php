<?php

namespace Tests\Feature\Api\v2;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for OrderController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\OrderController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('orders')]
class OrderControllerTest extends TestCase
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
    public function it_can_get_paginated_orders()
    {
        Order::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/orders/?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_user_orders()
    {
        Order::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v2/orders/users/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_find_user_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v2/orders/users/{$this->user->id}/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_user_order()
    {
        $response = $this->getJson("/api/v2/orders/users/{$this->user->id}/999999");

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_get_pending_count()
    {
        Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 1  // Use integer for OrderEnum
        ]);

        $response = $this->getJson("/api/v2/orders/users/{$this->user->id}/pending-count");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_orders_by_ids()
    {
        $order1 = Order::factory()->create(['user_id' => $this->user->id]);
        $order2 = Order::factory()->create(['user_id' => $this->user->id]);

        // Use POST request or query parameters correctly
        $response = $this->getJson("/api/v2/orders/users/{$this->user->id}/by-ids?ids={$order1->id},{$order2->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_dashboard_statistics()
    {
        Order::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/orders/dashboard/statistics');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_order()
    {
        $platform = \App\Models\Platform::factory()->create();

        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $platform->id,
            'note' => 'Test order'
        ];

        $response = $this->postJson('/api/v2/orders/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_order()
    {
        $response = $this->postJson('/api/v2/orders/', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_create_order_from_cart()
    {
        $platform = \App\Models\Platform::factory()->create();

        $data = [
            'user_id' => $this->user->id,
            'orders_data' => [
                [
                    'platform_id' => $platform->id,
                    'note' => 'Test cart order'
                ]
            ]
        ];

        $response = $this->postJson('/api/v2/orders/from-cart', $data);

        $response->assertStatus(201);
    }

    #[Test]
    public function it_can_cancel_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 1  // OrderEnum::New
        ]);

        $response = $this->postJson("/api/v2/orders/{$order->id}/cancel");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_make_order_ready()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 1  // OrderEnum::New
        ]);

        $response = $this->postJson("/api/v2/orders/{$order->id}/make-ready");

        $response->assertStatus(200);
    }
}

