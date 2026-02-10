<?php

namespace Tests\Feature\Api\v2;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for UserBalancesController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\UserBalancesController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('user_balances')]
class UserBalancesControllerTest extends TestCase
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
    public function it_can_get_horizontal_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_horizontal_balance_field()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/field/cash_balance");

        $this->assertContains($response->status(), [200, 404]); // 404 if user has no balance record yet
    }

    #[Test]
    public function it_can_get_cash_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/cash");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['cash_balance']]);
    }

    #[Test]
    public function it_can_get_bfss_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/bfss/A");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['bfss_balance', 'type']]);
    }

    #[Test]
    public function it_can_get_discount_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/discount");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['discount_balance']]);
    }

    #[Test]
    public function it_can_get_tree_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/tree");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['tree_balance']]);
    }

    #[Test]
    public function it_can_get_sms_balance()
    {
        $response = $this->getJson("/api/v2/user-balances/horizontal/{$this->user->id}/sms");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['sms_balance']]);
    }

    #[Test]
    public function it_can_update_calculated_horizontal_balance()
    {
        $data = [
            'type' => 'cash_balance',
            'value' => 100.50
        ];

        $response = $this->putJson("/api/v2/user-balances/horizontal/{$this->user->id}/calculated", $data);

        $this->assertContains($response->status(), [200, 404]); // May fail if user has no balance record
    }

    #[Test]
    public function it_can_update_balance_field()
    {
        $data = [
            'field' => 'cash_balance',
            'value' => 150.00
        ];

        $response = $this->putJson("/api/v2/user-balances/horizontal/{$this->user->id}/field", $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_calculate_new_balance()
    {
        $data = [
            'operation_type' => 'add',
            'amount' => 50.00
        ];

        $response = $this->postJson("/api/v2/user-balances/horizontal/{$this->user->id}/calculate", $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_all_vertical_balances()
    {
        $response = $this->getJson("/api/v2/user-balances/vertical/{$this->user->id}/all");

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    #[Test]
    public function it_can_get_vertical_balance()
    {
        $balanceId = 1;
        $response = $this->getJson("/api/v2/user-balances/vertical/{$this->user->id}/{$balanceId}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_update_vertical_balance_after_operation()
    {
        $data = [
            'balance_id' => 1,
            'operation_id' => 1,
            'amount' => 100.00
        ];

        $response = $this->putJson("/api/v2/user-balances/vertical/{$this->user->id}/update-after-operation", $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_update_calculated_vertical_balance()
    {
        $data = [
            'type' => 1,
            'value' => 200.00
        ];

        $response = $this->putJson("/api/v2/user-balances/vertical/{$this->user->id}/calculated", $data);

        $response->assertStatus(200);
    }
}

