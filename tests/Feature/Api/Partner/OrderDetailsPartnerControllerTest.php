<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
class OrderDetailsPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $order;
    protected $baseUrl = '/api/partner/orders/details';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->order = Order::factory()->create([
            'platform_id' => $platform->id,
            'user_id' => $this->user->id
        ]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_create_order_detail()
    {
        $item = \App\Models\Item::factory()->create();

        $data = [
            'order_id' => $this->order->id,
            'item_id' => $item->id,
            'qty' => 2,
            'unit_price' => 50.00,
            'created_by' => $this->user->id,
            'user_id' => $this->user->id
        ];
        $response = $this->postJson($this->baseUrl, $data);
     $response->assertStatus(201)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_update_order_detail()
    {
        $detail = OrderDetail::factory()->create(['order_id' => $this->order->id]);
        $data = [
            'qty' => 5,
            'updated_by' => $this->user->id,
            'user_id' => $this->user->id
        ];
        $response = $this->putJson($this->baseUrl . '/' . $detail->id, $data);
        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_create_fails_with_invalid_data()
    {
        $data = ['user_id' => $this->user->id];
        $response = $this->postJson($this->baseUrl, $data);
        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);
        $data = ['order_id' => $this->order->id, 'user_id' => $this->user->id];
        $response = $this->postJson($this->baseUrl, $data);
        $response->assertStatus(403)->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
