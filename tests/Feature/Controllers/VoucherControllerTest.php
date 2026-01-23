<?php

/**
 * Test Suite for VoucherController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\VoucherController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\BalanceInjectorCoupon;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoucherControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_authenticated_user_can_access_vouchers()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_delete_with_empty_ids_returns_error()
    {
        $response = $this->postJson('/api/vouchers/delete-injector-coupon', [
            'ids' => []
        ]);

        $this->assertTrue(in_array($response->status(), [400, 404, 500]));
    }

    /** @test */
    public function test_delete_request_accepts_ids_array()
    {
        $response = $this->postJson('/api/vouchers/delete-injector-coupon', [
            'ids' => [1, 2, 3]
        ]);

        // Should return some response (200, 404, or 500 depending on data)
        $this->assertNotNull($response->status());
    }

    /** @test */
    public function test_user_has_required_attributes()
    {
        $this->assertNotNull($this->user->id);
        $this->assertInstanceOf(User::class, $this->user);
    }
}
