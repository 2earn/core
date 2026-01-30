<?php
namespace Tests\Unit\Services\Coupon;
use App\Enums\CouponStatusEnum;
use App\Models\BalanceInjectorCoupon;
use App\Models\Coupon;
use App\Models\Platform;
use App\Models\User;
use App\Services\Coupon\CouponService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class CouponServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CouponService $couponService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = new CouponService();
    }
    /**
     * Test getById returns coupon when exists
     */
    public function test_get_by_id_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getById($coupon->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($coupon->id, $result->id);
    }
    /**
     * Test getByIdOrFail returns coupon
     */
    public function test_get_by_id_or_fail_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getByIdOrFail($coupon->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($coupon->id, $result->id);
    }
    /**
     * Test getUserCouponsPaginated returns paginated results
     */
    public function test_get_user_coupons_paginated_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceInjectorCoupon::factory()->count(15)->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getUserCouponsPaginated($user->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThan(0, $result->total());
    }
    /**
     * Test getUserCoupons returns collection
     */
    public function test_get_user_coupons_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceInjectorCoupon::factory()->count(5)->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getUserCoupons($user->id);
        // Assert
        $this->assertCount(5, $result);
    }
    /**
     * Test deleteMultiple deletes unconsumed coupons only
     */
    public function test_delete_multiple_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon1 = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        $coupon2 = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        $coupon3 = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id, 'consumed' => 1]);
        // Act
        $result = $this->couponService->deleteMultiple([$coupon1->id, $coupon2->id, $coupon3->id], $user->id);
        // Assert
        $this->assertEquals(2, $result);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon1->id]);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon2->id]);
        $this->assertDatabaseHas('balance_injector_coupons', ['id' => $coupon3->id]);
    }
    /**
     * Test delete removes single coupon
     */
    public function test_delete_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        // Act
        $result = $this->couponService->delete($coupon->id, $user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon->id]);
    }
    /**
     * Test consume marks coupon as consumed
     */
    public function test_consume_works()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        $coupon = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        // Act
        $result = $this->couponService->consume($coupon->id, $user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('balance_injector_coupons', ['id' => $coupon->id, 'consumed' => 1]);
    }
    /**
     * Test getCouponsPaginated returns paginated results
     */
    public function test_get_coupons_paginated_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceInjectorCoupon::factory()->count(15)->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getCouponsPaginated();
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(10, $result->perPage());
    }
    /**
     * Test deleteById removes coupon
     */
    public function test_delete_by_id_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = BalanceInjectorCoupon::factory()->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->deleteById($coupon->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('balance_injector_coupons', ['id' => $coupon->id]);
    }
    /**
     * Test getMaxAvailableAmount returns correct amount
     */
    public function test_get_max_available_amount_works()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        $platform = Platform::factory()->create();
        Coupon::factory()->count(3)->create([
            'platform_id' => $platform->id,
            'status' => CouponStatusEnum::available->value,
            'value' => 100
        ]);
        // Act
        $result = $this->couponService->getMaxAvailableAmount($platform->id);
        // Assert
        $this->assertGreaterThan(0, $result);
    }
    /**
     * Test deleteMultipleByIds deletes unconsumed coupons
     */
    public function test_delete_multiple_by_ids_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon1 = Coupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        $coupon2 = Coupon::factory()->create(['user_id' => $user->id, 'consumed' => 0]);
        // Act
        $result = $this->couponService->deleteMultipleByIds([$coupon1->id, $coupon2->id]);
        // Assert
        $this->assertEquals(2, $result);
    }
    /**
     * Test getAllCouponsOrdered returns ordered collection
     */
    public function test_get_all_coupons_ordered_works()
    {
        // Arrange
        $user = User::factory()->create();
        Coupon::factory()->count(5)->create(['user_id' => $user->id]);
        // Act
        $result = $this->couponService->getAllCouponsOrdered();
        // Assert
        $this->assertNotNull($result);
        $this->assertGreaterThanOrEqual(5, $result->count());
    }
    /**
     * Test getPurchasedCouponsPaginated returns paginated results
     */
    public function test_get_purchased_coupons_paginated_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create coupons without factory to avoid user conflicts
        for ($i = 0; $i < 15; $i++) {
            Coupon::create([
                'pin' => 'PIN' . uniqid() . $i,
                'sn' => 'SN' . uniqid() . $i,
                'value' => 100,
                'user_id' => $user->id,
                'platform_id' => $platform->id,
                'status' => CouponStatusEnum::available->value,
                'consumed' => 0,
                'attachment_date' => now(),
                'purchase_date' => now(),
            ]);
        }

        // Act
        $result = $this->couponService->getPurchasedCouponsPaginated($user->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(10, $result->perPage());
    }
    /**
     * Test getPurchasedCouponsByStatus returns filtered collection
     */
    public function test_get_purchased_coupons_by_status_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create coupons with specific user_id and status
        $createdCoupons = [];
        for ($i = 0; $i < 3; $i++) {
            $coupon = Coupon::create([
                'pin' => 'PINA' . uniqid() . $i,
                'sn' => 'SNA' . uniqid() . $i,
                'value' => 100,
                'user_id' => $user->id,
                'platform_id' => $platform->id,
                'status' => 0, // available status as integer
                'consumed' => 0,
                'attachment_date' => now(),
            ]);
            $createdCoupons[] = $coupon;
        }

        for ($i = 0; $i < 2; $i++) {
            Coupon::create([
                'pin' => 'PINC' . uniqid() . ($i + 10),
                'sn' => 'SNC' . uniqid() . ($i + 10),
                'value' => 50,
                'user_id' => $user->id,
                'platform_id' => $platform->id,
                'status' => 3, // consumed status as integer
                'consumed' => 1,
                'attachment_date' => now(),
            ]);
        }

        // Verify coupons were created
        $this->assertCount(3, $createdCoupons);

        // Act - Pass integer status
        $result = $this->couponService->getPurchasedCouponsByStatus($user->id, 0);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count(), 'Expected at least 3 coupons with available status');
        foreach ($result as $coupon) {
            $this->assertEquals($user->id, $coupon->user_id);
        }
    }
    /**
     * Test markAsConsumed updates coupon status
     */
    public function test_mark_as_consumed_works()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = Coupon::factory()->create([
            'user_id' => $user->id,
            'consumed' => 0
        ]);
        // Act
        $result = $this->couponService->markAsConsumed($coupon->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'consumed' => 1]);
    }
    /**
     * Test getBySn returns coupon by serial number
     */
    public function test_get_by_sn_works()
    {
        // Arrange
        $coupon = Coupon::factory()->create(['sn' => 'TEST123456']);
        // Act
        $result = $this->couponService->getBySn('TEST123456');
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('TEST123456', $result->sn);
    }
    /**
     * Test findCouponById returns coupon
     */
    public function test_find_coupon_by_id_works()
    {
        // Arrange
        $coupon = Coupon::factory()->create();
        // Act
        $result = $this->couponService->findCouponById($coupon->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($coupon->id, $result->id);
    }
    /**
     * Test updateCoupon updates coupon data
     */
    public function test_update_coupon_works()
    {
        // Arrange
        $coupon = Coupon::factory()->create(['value' => 100]);
        $data = ['value' => 200];
        // Act
        $result = $this->couponService->updateCoupon($coupon, $data);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'value' => 200]);
    }
    /**
     * Test getAvailableCouponsForPlatform returns available coupons
     */
    public function test_get_available_coupons_for_platform_works()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        $platform = Platform::factory()->create();

        // Create coupons without factory to avoid user conflicts
        for ($i = 0; $i < 5; $i++) {
            Coupon::create([
                'pin' => 'PIN' . uniqid() . $i,
                'sn' => 'SN' . uniqid() . $i,
                'value' => 100,
                'user_id' => $user->id,
                'platform_id' => $platform->id,
                'status' => CouponStatusEnum::available->value,
                'consumed' => 0,
                'attachment_date' => now(),
            ]);
        }

        // Act
        $result = $this->couponService->getAvailableCouponsForPlatform($platform->id, $user->id);
        // Assert
        $this->assertGreaterThanOrEqual(5, $result->count());
    }
    /**
     * Test createMultipleCoupons creates coupons
     */
    public function test_create_multiple_coupons_works()
    {
        // Arrange
        $platform = Platform::factory()->create();
        $pins = ['PIN1', 'PIN2', 'PIN3'];
        $sns = ['SN1', 'SN2', 'SN3'];
        $data = [
            'platform_id' => $platform->id,
            'value' => 100,
            'status' => CouponStatusEnum::available->value
        ];
        // Act
        $result = $this->couponService->createMultipleCoupons($pins, $sns, $data);
        // Assert
        $this->assertEquals(3, $result);
        $this->assertDatabaseHas('coupons', ['pin' => 'PIN1']);
        $this->assertDatabaseHas('coupons', ['pin' => 'PIN2']);
        $this->assertDatabaseHas('coupons', ['pin' => 'PIN3']);
    }
    /**
     * Test buyCoupon purchases coupon
     */
    public function test_buy_coupon_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create available coupons
        $coupon1 = Coupon::factory()->create([
            'platform_id' => $platform->id,
            'sn' => 'SN001',
            'value' => 50,
            'status' => CouponStatusEnum::available->value,
            'consumed' => false
        ]);
        $coupon2 = Coupon::factory()->create([
            'platform_id' => $platform->id,
            'sn' => 'SN002',
            'value' => 50,
            'status' => CouponStatusEnum::available->value,
            'consumed' => false
        ]);

        $couponsData = [
            ['sn' => 'SN001', 'value' => 50],
            ['sn' => 'SN002', 'value' => 50]
        ];

        // Create an item for the order
        $item = \App\Models\Item::factory()->create(['platform_id' => $platform->id]);

        // Act
        $result = $this->couponService->buyCoupon(
            $couponsData,
            $user->id,
            $platform->id,
            $platform->name,
            $item->id
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
    }
    /**
     * Test getCouponsForAmount returns coupons for amount
     */
    public function test_get_coupons_for_amount_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create available coupons with different values without factory
        Coupon::create([
            'pin' => 'PIN' . uniqid() . '1',
            'sn' => 'SN' . uniqid() . '1',
            'value' => 25,
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'status' => CouponStatusEnum::available->value,
            'consumed' => 0,
            'attachment_date' => now(),
        ]);
        Coupon::create([
            'pin' => 'PIN' . uniqid() . '2',
            'sn' => 'SN' . uniqid() . '2',
            'value' => 50,
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'status' => CouponStatusEnum::available->value,
            'consumed' => 0,
            'attachment_date' => now(),
        ]);
        Coupon::create([
            'pin' => 'PIN' . uniqid() . '3',
            'sn' => 'SN' . uniqid() . '3',
            'value' => 100,
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'status' => CouponStatusEnum::available->value,
            'consumed' => 0,
            'attachment_date' => now(),
        ]);

        // Act - Request coupons for amount 100
        $result = $this->couponService->getCouponsForAmount(
            $platform->id,
            $user->id,
            100,
            30 // 30 minutes reservation
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('amount', $result);
        $this->assertArrayHasKey('coupons', $result);
        $this->assertArrayHasKey('lastValue', $result);
        $this->assertGreaterThan(0, $result['amount']);
        $this->assertLessThanOrEqual(100, $result['amount']);
    }
    /**
     * Test simulateCouponPurchase simulates purchase
     */
    public function test_simulate_coupon_purchase_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create available coupons
        Coupon::factory()->count(5)->create([
            'platform_id' => $platform->id,
            'value' => 20,
            'status' => CouponStatusEnum::available->value
        ]);

        // Act - Simulate purchase for 80 (should get 4 coupons of 20)
        $result = $this->couponService->simulateCouponPurchase(
            $platform->id,
            $user->id,
            80,
            30 // 30 minutes reservation
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);

        if ($result['success']) {
            $this->assertArrayHasKey('equal', $result);
            $this->assertArrayHasKey('preSimulationResult', $result);
            $this->assertArrayHasKey('alternativeResult', $result);
            $this->assertArrayHasKey('amount', $result);
            $this->assertArrayHasKey('coupons', $result);
        }
    }
}
