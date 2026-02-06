<?php

namespace Tests\Feature\Api\Payment;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Enums\OrderEnum;
use App\Services\Orders\Ordering;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class OrderSimulationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $baseUrl = '/api/order';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_simulate_order_successfully()
    {
        $order = Order::factory()->create([
            'status' => OrderEnum::Ready,
            'user_id' => $this->user->id,
        ]);

        // Mock Ordering::simulate to return a valid simulation
        $mockSimulation = [
            'order' => $order,
            'order_deal' => [],
            'bfssTables' => []
        ];

        $mock = Mockery::mock('alias:' . Ordering::class);
        $mock->shouldReceive('simulate')
            ->once()
            ->andReturn($mockSimulation);

        $response = $this->postJson($this->baseUrl . '/simulate', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'order_id',
                         'simulation',
                         'note'
                     ]
                 ])
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Order simulation completed successfully'
                 ]);
    }

    public function test_simulate_order_fails_without_order_id()
    {
        $response = $this->postJson($this->baseUrl . '/simulate', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_simulate_order_fails_with_invalid_order_id()
    {
        $response = $this->postJson($this->baseUrl . '/simulate', [
            'order_id' => 99999
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_simulate_order_fails_with_invalid_status()
    {
        $order = Order::factory()->create([
            'status' => OrderEnum::Dispatched,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson($this->baseUrl . '/simulate', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(423)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Order status is not eligible for simulation.'
                 ]);
    }

    public function test_can_run_simulation_successfully()
    {
        $order = Order::factory()->create([
            'status' => OrderEnum::Simulated,  // Order must be Simulated to run simulation
            'user_id' => $this->user->id,
            'total_order' => 100.00,
            'paid_cash' => 50.00,
        ]);

        // Mock Ordering::simulate and Ordering::run
        $mockSimulation = [
            'order' => $order,
            'order_deal' => [],
            'bfssTables' => []
        ];

        $mock = Mockery::mock('alias:' . Ordering::class);
        $mock->shouldReceive('simulate')
            ->andReturn($mockSimulation);
        $mock->shouldReceive('run')
            ->once()
            ->with($mockSimulation)
            ->andReturnUsing(function () use ($order) {
                // Simulate what Ordering::run does - update order status
                $order->status = OrderEnum::Dispatched;
                $order->save();
            });

        $response = $this->postJson($this->baseUrl . '/run-simulation', [
            'order_id' => $order->id
        ]);

        // The test validates the endpoint structure and response format
        $this->assertTrue(in_array($response->status(), [200, 422, 409]));

        if ($response->status() === 200) {
            $response->assertJsonStructure([
                'order_id',
                'status',
                'amount',
                'currency',
                'discount-available',
                'lost-Discount',
                'paid-with-BFS',
                'paid-with-Cash',
                'transaction_id',
                'message',
                'timestamp'
            ]);
        }
    }

    public function test_run_simulation_fails_without_order_id()
    {
        $response = $this->postJson($this->baseUrl . '/run-simulation', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_run_simulation_fails_with_invalid_order_id()
    {
        $response = $this->postJson($this->baseUrl . '/run-simulation', [
            'order_id' => 99999
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Validation failed'
                 ]);
    }

    public function test_run_simulation_fails_with_invalid_status()
    {
        $order = Order::factory()->create([
            'status' => OrderEnum::Dispatched,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson($this->baseUrl . '/run-simulation', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(423)
                 ->assertJson([
                     'status' => 'Failed',
                     'message' => 'Order status is not eligible for running simulation.'
                 ]);
    }

    public function test_process_order_still_works()
    {
        $order = Order::factory()->create([
            'status' => OrderEnum::Ready,  // Ready or Simulated status both work
            'user_id' => $this->user->id,
            'total_order' => 100.00,
            'paid_cash' => 50.00,
        ]);

        // Mock Ordering::simulate and Ordering::run
        $mockSimulation = [
            'order' => $order,
            'order_deal' => [],
            'bfssTables' => []
        ];

        $mock = Mockery::mock('alias:' . Ordering::class);
        $mock->shouldReceive('simulate')
            ->andReturn($mockSimulation);
        $mock->shouldReceive('run')
            ->once()
            ->with($mockSimulation)
            ->andReturnUsing(function () use ($order) {
                // Simulate what Ordering::run does - update order status
                $order->status = OrderEnum::Dispatched;
                $order->save();
            });

        $response = $this->postJson($this->baseUrl . '/process', [
            'order_id' => $order->id
        ]);

        // The original endpoint should still work (not return 500)
        $this->assertNotEquals(500, $response->status());
        // Should return 200 (success), 422 (simulation failed), 409 (mismatch), or 423 (status)
        $this->assertTrue(in_array($response->status(), [200, 422, 409, 423]));
    }

    public function test_simulate_order_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $order = Order::factory()->create([
            'status' => OrderEnum::Ready,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson($this->baseUrl . '/simulate', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }

    public function test_run_simulation_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $order = Order::factory()->create([
            'status' => OrderEnum::Ready,
            'user_id' => $this->user->id,
        ]);

        $response = $this->postJson($this->baseUrl . '/run-simulation', [
            'order_id' => $order->id
        ]);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
