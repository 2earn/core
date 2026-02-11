<?php

namespace Tests\Feature\Api\v2;

use App\Models\BalanceInjectorCoupon;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for BalanceInjectorCouponController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\BalanceInjectorCouponController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('balance_injector_coupon')]
class BalanceInjectorCouponControllerTest extends TestCase
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
        BalanceInjectorCoupon::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/balance-injector-coupons?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_coupons()
    {
        BalanceInjectorCoupon::factory()->create(['pin' => 'TEST123']);
        BalanceInjectorCoupon::factory()->create(['pin' => 'OTHER456']);

        $response = $this->getJson('/api/v2/balance-injector-coupons?search=TEST');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_sort_coupons()
    {
        BalanceInjectorCoupon::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/balance-injector-coupons?sort_field=value&sort_direction=desc');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_pagination_parameters()
    {
        $response = $this->getJson('/api/v2/balance-injector-coupons?per_page=200');

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_get_all_coupons()
    {
        BalanceInjectorCoupon::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/balance-injector-coupons/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_coupon_by_id()
    {
        $coupon = BalanceInjectorCoupon::factory()->create();

        $response = $this->getJson("/api/v2/balance-injector-coupons/{$coupon->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_coupon()
    {
        $response = $this->getJson('/api/v2/balance-injector-coupons/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_delete_coupon()
    {
        $coupon = BalanceInjectorCoupon::factory()->create();

        $response = $this->deleteJson("/api/v2/balance-injector-coupons/{$coupon->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon->id]);
    }
}

