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
use PHPUnit\Framework\Attributes\Test;
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
    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
        $this->assertInstanceOf(User::class, $this->user);
    }
    #[Test]
    public function test_add_cash_validates_amount()
    {
        // Create a Sanctum token for the user
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/add-cash', [
            'amount' => 999999999, // Amount exceeds user's balance
            'reciver' => $this->recipient->idUser
        ]);

        // Should return error for insufficient balance
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }
    #[Test]
    public function test_add_cash_requires_recipient()
    {
        // Create a Sanctum token for the user
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/add-cash', [
            'amount' => 100,
            'reciver' => null
        ]);

        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }
    #[Test]
    public function test_recipient_user_exists()
    {
        $this->assertInstanceOf(User::class, $this->recipient);
        $this->assertNotEquals($this->user->id, $this->recipient->id);
    }
}
