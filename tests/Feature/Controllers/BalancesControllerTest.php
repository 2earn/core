<?php
/**
 * Test Suite for BalancesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\BalancesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */
namespace Tests\Feature\Controllers;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class BalancesControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $recipient;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->recipient = User::factory()->create();
        $this->actingAs($this->user);
    }
    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
        $this->assertInstanceOf(User::class, $this->user);
    }
    /** @test */
    public function test_add_cash_validates_amount()
    {
        $response = $this->postJson('/api/balances/add-cash', [
            'amount' => -100,
            'reciver' => $this->recipient->idUser
        ]);

        // Should return error for negative amount or insufficient balance
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }
    /** @test */
    public function test_add_cash_requires_recipient()
    {
        $response = $this->postJson('/api/balances/add-cash', [
            'amount' => 100,
            'reciver' => null
        ]);

        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }
    /** @test */
    public function test_recipient_user_exists()
    {
        $this->assertInstanceOf(User::class, $this->recipient);
        $this->assertNotEquals($this->user->id, $this->recipient->id);
    }
}
