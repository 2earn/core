<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SalesDashboardControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $deal;
    protected $baseUrl = '/api/partner/sales/dashboard';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->deal = Deal::factory()->create(['platform_id' => $this->platform->id]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_get_kpis()
    {
        Order::factory()->count(10)->create([
            'deal_id' => $this->deal->id,
            'total_amount' => 100.00
        ]);

        $response = $this->getJson($this->baseUrl . '/kpis?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_sales_evolution_chart()
    {
        Order::factory()->count(5)->create([
            'deal_id' => $this->deal->id,
            'created_at' => now()->subDays(5)
        ]);

        $response = $this->getJson($this->baseUrl . '/evolution-chart?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_top_selling_products()
    {
        Order::factory()->count(10)->create([
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/top-products?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_top_selling_deals()
    {
        Order::factory()->count(8)->create([
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/top-deals?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_transactions()
    {
        Order::factory()->count(15)->create([
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/transactions?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_transactions_details()
    {
        Order::factory()->count(5)->create([
            'deal_id' => $this->deal->id
        ]);

        $response = $this->getJson($this->baseUrl . '/transactions/details?user_id=' . $this->user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_kpis_with_date_range()
    {
        $response = $this->getJson($this->baseUrl . '/kpis?user_id=' . $this->user->id . '&start_date=2026-01-01&end_date=2026-01-31');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_evolution_chart_with_period()
    {
        $response = $this->getJson($this->baseUrl . '/evolution-chart?user_id=' . $this->user->id . '&period=week');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_fails_without_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/kpis');

        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        $response = $this->getJson($this->baseUrl . '/kpis?user_id=' . $this->user->id);

        $response->assertStatus(403)
                 ->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
