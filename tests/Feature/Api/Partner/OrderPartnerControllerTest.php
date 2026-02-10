<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
class OrderPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $deal;
    protected $baseUrl = '/api/partner/orders';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test Partner User',
            'email' => 'partner@test.com',
        ]);

        $this->platform = Platform::factory()->create([
            'created_by' => $this->user->id,
            'enabled' => true
        ]);

        $this->deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_list_orders()
    {
        Order::factory()->count(5)->create([
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson($this->baseUrl . '/orders?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_show_single_order()
    {
        $order = Order::factory()->create([
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson($this->baseUrl . '/orders/' . $order->id . '?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_create_order()
    {
        $orderData = [
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id,
            'total_order' => 200.00,
            'created_by' => $this->user->id,
            'status' => 'new'
        ];

        $response = $this->postJson($this->baseUrl . '/orders', $orderData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'platform_id', 'user_id', 'total_order', 'status']);
    }

    public function test_can_update_order()
    {
        $order = Order::factory()->create([
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id
        ]);

        $updateData = [
            'total_order' => 500.00,
            'updated_by' => $this->user->id,
            'user_id' => $this->user->id
        ];

        $response = $this->putJson($this->baseUrl . '/orders/' . $order->id, $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_change_order_status()
    {
        $order = Order::factory()->create([
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id,
            'status' => 1
        ]);

        $statusData = [
            'status' => 2, // Ready status (only valid status for this endpoint)
            'user_id' => $this->user->id
        ];

        $response = $this->patchJson($this->baseUrl . '/' . $order->id . '/status', $statusData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message']);
    }

    public function test_list_orders_with_pagination()
    {
        Order::factory()->count(15)->create([
            'platform_id' => $this->platform->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson($this->baseUrl . '/orders?user_id=' . $this->user->id . '&page=1&limit=10');

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(15, count($response->json('data')));
    }

    public function test_fails_without_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/orders');

        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/orders?user_id=' . $this->user->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
