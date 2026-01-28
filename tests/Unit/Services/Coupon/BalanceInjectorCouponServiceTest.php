<?php

namespace Tests\Unit\Services\Coupon;

use App\Models\BalanceInjectorCoupon;
use App\Models\User;
use App\Services\Coupon\BalanceInjectorCouponService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BalanceInjectorCouponServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected BalanceInjectorCouponService $balanceInjectorCouponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceInjectorCouponService = new BalanceInjectorCouponService();
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        BalanceInjectorCoupon::factory()->count(15)->create();

        // Act
        $result = $this->balanceInjectorCouponService->getPaginated(null, 'created_at', 'desc', 10);

        // Assert
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual(15, $result->total());
    }

    /**
     * Test getPaginated with search filters by PIN
     */
    public function test_get_paginated_filters_by_pin()
    {
        // Arrange
        $coupon = BalanceInjectorCoupon::factory()->create(['pin' => 'TEST123456']);
        BalanceInjectorCoupon::factory()->count(5)->create();

        // Act
        $result = $this->balanceInjectorCouponService->getPaginated('TEST123');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getPaginated sorts by specified field
     */
    public function test_get_paginated_sorts_by_field()
    {
        // Arrange
        $coupon1 = BalanceInjectorCoupon::factory()->create(['value' => 50]);
        $coupon2 = BalanceInjectorCoupon::factory()->create(['value' => 100]);

        // Act
        $result = $this->balanceInjectorCouponService->getPaginated(null, 'value', 'desc', 10);

        // Assert
        $this->assertGreaterThanOrEqual($result->items()[1]->value, $result->items()[0]->value);
    }

    /**
     * Test getById returns coupon
     */
    public function test_get_by_id_returns_coupon()
    {
        // Arrange
        $coupon = BalanceInjectorCoupon::factory()->create();

        // Act
        $result = $this->balanceInjectorCouponService->getById($coupon->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($coupon->id, $result->id);
    }

    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->balanceInjectorCouponService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByIdOrFail returns coupon
     */
    public function test_get_by_id_or_fail_returns_coupon()
    {
        // Arrange
        $coupon = BalanceInjectorCoupon::factory()->create();

        // Act
        $result = $this->balanceInjectorCouponService->getByIdOrFail($coupon->id);

        // Assert
        $this->assertInstanceOf(BalanceInjectorCoupon::class, $result);
        $this->assertEquals($coupon->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception
     */
    public function test_get_by_id_or_fail_throws_exception()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->balanceInjectorCouponService->getByIdOrFail(99999);
    }

    /**
     * Test delete deletes coupon
     */
    public function test_delete_deletes_coupon()
    {
        // Arrange
        $coupon = BalanceInjectorCoupon::factory()->create();

        // Act
        $result = $this->balanceInjectorCouponService->delete($coupon->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon->id]);
    }

    /**
     * Test deleteMultiple deletes only unconsumed coupons
     */
    public function test_delete_multiple_deletes_only_unconsumed()
    {
        // Arrange
        $unconsumed1 = BalanceInjectorCoupon::factory()->unconsumed()->create();
        $unconsumed2 = BalanceInjectorCoupon::factory()->unconsumed()->create();
        $consumed = BalanceInjectorCoupon::factory()->consumed()->create();

        $ids = [$unconsumed1->id, $unconsumed2->id, $consumed->id];

        // Act
        $result = $this->balanceInjectorCouponService->deleteMultiple($ids);

        // Assert
        $this->assertEquals(2, $result);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $unconsumed1->id]);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $unconsumed2->id]);
        $this->assertDatabaseHas('balance_injector_coupons', ['id' => $consumed->id]);
    }

    /**
     * Test getAll returns all coupons
     */
    public function test_get_all_returns_all_coupons()
    {
        // Arrange
        $initialCount = BalanceInjectorCoupon::count();
        BalanceInjectorCoupon::factory()->count(5)->create();

        // Act
        $result = $this->balanceInjectorCouponService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 5, $result->count());
    }

    /**
     * Test getByPin returns coupon by PIN
     */
    public function test_get_by_pin_returns_coupon()
    {
        // Arrange
        $coupon = BalanceInjectorCoupon::factory()->create(['pin' => 'UNIQUEPIN123']);

        // Act
        $result = $this->balanceInjectorCouponService->getByPin('UNIQUEPIN123');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($coupon->id, $result->id);
        $this->assertEquals('UNIQUEPIN123', $result->pin);
    }

    /**
     * Test getByPin returns null for non-existent PIN
     */
    public function test_get_by_pin_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->balanceInjectorCouponService->getByPin('NONEXISTENT');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByUserId returns user's coupons
     */
    public function test_get_by_user_id_returns_user_coupons()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceInjectorCoupon::factory()->count(3)->consumed()->create(['user_id' => $user->id]);
        BalanceInjectorCoupon::factory()->count(2)->create(); // Other users

        // Act
        $result = $this->balanceInjectorCouponService->getByUserId($user->id);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());

        foreach ($result as $coupon) {
            $this->assertEquals($user->id, $coupon->user_id);
        }
    }

    /**
     * Test getByUserId orders by created_at desc
     */
    public function test_get_by_user_id_orders_by_created_at_desc()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon1 = BalanceInjectorCoupon::factory()->consumed()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2)
        ]);
        $coupon2 = BalanceInjectorCoupon::factory()->consumed()->create([
            'user_id' => $user->id,
            'created_at' => now()
        ]);

        // Act
        $result = $this->balanceInjectorCouponService->getByUserId($user->id);

        // Assert
        $this->assertEquals($coupon2->id, $result->first()->id);
    }

    /**
     * Test createMultipleCoupons creates coupons
     */
    public function test_create_multiple_coupons_creates_coupons()
    {
        // Arrange
        $numberOfCoupons = 5;
        $couponData = [
            'attachment_date' => now(),
            'value' => 100,
            'category' => 1,
            'consumed' => 0,
        ];

        // Act
        $result = $this->balanceInjectorCouponService->createMultipleCoupons($numberOfCoupons, $couponData, '100.00');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(5, $result['created_count']);
        $this->assertStringContainsString('coupons created successfully', $result['message']);
    }

    /**
     * Test createMultipleCoupons validates number of coupons
     */
    public function test_create_multiple_coupons_validates_number()
    {
        // Arrange
        $couponData = [
            'attachment_date' => now(),
            'value' => 100,
            'category' => 1,
            'consumed' => 0,
        ];

        // Act
        $result = $this->balanceInjectorCouponService->createMultipleCoupons(150, $couponData, '100.00');

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('less than 100', $result['message']);
    }

    /**
     * Test createMultipleCoupons with category 2 type
     */
    public function test_create_multiple_coupons_with_category_2()
    {
        // Arrange
        $couponData = [
            'attachment_date' => now(),
            'value' => 100,
            'category' => 2,
            'consumed' => 0,
        ];

        // Act
        $result = $this->balanceInjectorCouponService->createMultipleCoupons(3, $couponData, null);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['created_count']);
    }
}

        $this->markTestIncomplete('Test for deleteMultiple not yet implemented');
    }

    /**
     * Test getAll method
     * TODO: Implement actual test logic
     */
    public function test_get_all_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getAll();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getAll not yet implemented');
    }

    /**
     * Test getByPin method
     * TODO: Implement actual test logic
     */
    public function test_get_by_pin_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getByPin();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getByPin not yet implemented');
    }

    /**
     * Test getByUserId method
     * TODO: Implement actual test logic
     */
    public function test_get_by_user_id_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getByUserId();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getByUserId not yet implemented');
    }

    /**
     * Test createMultipleCoupons method
     * TODO: Implement actual test logic
     */
    public function test_create_multiple_coupons_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->createMultipleCoupons();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for createMultipleCoupons not yet implemented');
    }
}
