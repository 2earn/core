<?php

namespace Tests\Feature\Api\v2;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for CommissionBreakDownController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\CommissionBreakDownController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('commission_breakdown')]
class CommissionBreakDownControllerTest extends TestCase
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
    public function it_can_get_commission_breakdown_by_id()
    {
        $commission = CommissionBreakDown::factory()->create();

        $response = $this->getJson("/api/v2/commission-breakdowns/{$commission->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_commission_breakdown()
    {
        $response = $this->getJson('/api/v2/commission-breakdowns/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_get_commission_breakdowns_by_deal()
    {
        $deal = Deal::factory()->create();
        CommissionBreakDown::factory()->count(3)->create(['deal_id' => $deal->id]);

        $response = $this->getJson("/api/v2/commission-breakdowns/by-deal?deal_id={$deal->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_deal_id_parameter()
    {
        $response = $this->getJson('/api/v2/commission-breakdowns/by-deal');

        $response->assertStatus(422)
            ->assertJsonStructure(['status', 'errors']);
    }

    #[Test]
    public function it_can_sort_commission_breakdowns()
    {
        $deal = Deal::factory()->create();
        CommissionBreakDown::factory()->count(3)->create(['deal_id' => $deal->id]);

        $response = $this->getJson("/api/v2/commission-breakdowns/by-deal?deal_id={$deal->id}&order_by=commission_value&order_direction=DESC");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_calculate_commission_totals()
    {
        $deal = Deal::factory()->create();
        CommissionBreakDown::factory()->count(3)->create([
            'deal_id' => $deal->id,
            'commission_value' => 100
        ]);

        $response = $this->getJson("/api/v2/commission-breakdowns/calculate-totals/{$deal->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_commission_breakdown()
    {
        $deal = Deal::factory()->create();

        $data = [
            'deal_id' => $deal->id,
            'commission_value' => 150.00,
            'camembert' => 25.5
        ];

        $response = $this->postJson('/api/v2/commission-breakdowns', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('commission_break_downs', [
            'deal_id' => $deal->id,
            'commission_value' => 150.00
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating()
    {
        $response = $this->postJson('/api/v2/commission-breakdowns', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_commission_breakdown()
    {
        $commission = CommissionBreakDown::factory()->create(['commission_value' => 100]);

        $data = ['commission_value' => 200];

        $response = $this->putJson("/api/v2/commission-breakdowns/{$commission->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('commission_break_downs', [
            'id' => $commission->id,
            'commission_value' => 200
        ]);
    }

    #[Test]
    public function it_can_delete_commission_breakdown()
    {
        $commission = CommissionBreakDown::factory()->create();

        $response = $this->deleteJson("/api/v2/commission-breakdowns/{$commission->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('commission_break_downs', ['id' => $commission->id]);
    }

    // TODO: Implement getSummaryByUser endpoint in CommissionBreakDownController
    // #[Test]
    // public function it_can_get_commission_summary_by_user()
    // {
    //     $response = $this->getJson("/api/v2/commission-breakdowns/summary/user/{$this->user->id}");
    //
    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['status' => true]);
    // }
}

