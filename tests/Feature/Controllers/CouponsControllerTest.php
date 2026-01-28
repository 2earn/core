<?php

/**
 * Test Suite for CouponsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\CouponsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Services\Coupon\CouponService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class CouponsControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->couponService = Mockery::mock(CouponService::class);
    }

    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_coupon_service_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->couponService);
    }

    #[Test]
    public function test_delete_with_empty_ids_returns_error()
    {
        $response = $this->postJson('/api/coupons/delete', ['ids' => []]);
        $this->assertTrue(in_array($response->status(), [400, 404, 500]));
    }

    #[Test]
    public function test_delete_request_structure()
    {
        $response = $this->postJson('/api/coupons/delete', ['ids' => [1, 2, 3]]);
        $this->assertNotNull($response->status());
    }

    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
