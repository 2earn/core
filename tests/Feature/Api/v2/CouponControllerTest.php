<?php

namespace Tests\Feature\Api\v2;

use App\Models\Coupon;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for CouponController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\CouponController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('coupons')]
class CouponControllerTest extends TestCase
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
    public function it_can_get_paginated_coupons()
    {
        Coupon::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/coupons/');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_all_coupons()
    {
        Coupon::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/coupons/all');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_coupon_by_sn()
    {
        $coupon = Coupon::factory()->create(['sn' => 'TEST-123']);

        $response = $this->getJson('/api/v2/coupons/by-sn?sn=TEST-123');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_user_coupons()
    {
        Coupon::factory()->count(2)->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v2/coupons/users/{$this->user->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_purchased_coupons()
    {
        $response = $this->getJson("/api/v2/coupons/users/{$this->user->id}/purchased");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_purchased_by_status()
    {
        $response = $this->getJson("/api/v2/coupons/users/{$this->user->id}/status/active");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_available_for_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/coupons/platforms/{$platform->id}/available");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_max_available_amount()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/coupons/platforms/{$platform->id}/max-amount");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_simulate_purchase()
    {
        $data = [
            'platform_id' => 1,
            'amount' => 100,
            'user_id' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/coupons/simulate', $data);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_buy_coupon()
    {
        $data = [
            'platform_id' => 1,
            'amount' => 50,
            'user_id' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/coupons/buy', $data);

        $response->assertStatus(201);
    }

    #[Test]
    public function it_can_consume_coupon()
    {
        $coupon = Coupon::factory()->create(['status' => 'active']);

        $response = $this->postJson("/api/v2/coupons/{$coupon->id}/consume");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_mark_coupon_as_consumed()
    {
        $coupon = Coupon::factory()->create(['status' => 'active']);

        $response = $this->postJson("/api/v2/coupons/{$coupon->id}/mark-consumed");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_coupon_by_id()
    {
        $coupon = Coupon::factory()->create();

        $response = $this->getJson("/api/v2/coupons/{$coupon->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_delete_coupon()
    {
        $coupon = Coupon::factory()->create();

        $response = $this->deleteJson("/api/v2/coupons/{$coupon->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_delete_multiple_coupons()
    {
        $coupon1 = Coupon::factory()->create();
        $coupon2 = Coupon::factory()->create();

        $data = ['ids' => [$coupon1->id, $coupon2->id]];

        $response = $this->deleteJson('/api/v2/coupons/multiple', $data);

        $response->assertStatus(200);
    }
}

