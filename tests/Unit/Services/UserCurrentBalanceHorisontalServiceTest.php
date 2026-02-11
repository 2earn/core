<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserCurrentBalanceHorisontal;
use App\Services\UserBalances\UserCurrentBalanceHorisontalService;
use Tests\TestCase;

class UserCurrentBalanceHorisontalServiceTest extends TestCase
{

    protected UserCurrentBalanceHorisontalService $userCurrentBalanceHorisontalService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userCurrentBalanceHorisontalService = new UserCurrentBalanceHorisontalService();
    }

    /**
     * Test getStoredUserBalances method
     */
    public function test_get_stored_user_balances_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceHorisontal::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredUserBalances($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(UserCurrentBalanceHorisontal::class, $result);
        $this->assertEquals($balance->id, $result->id);
    }

    /**
     * Test getStoredCash method
     */
    public function test_get_stored_cash_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'cash_balance' => 500.50,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredCash($user->id);

        // Assert
        $this->assertEquals(500.50, $result);
    }

    /**
     * Test getStoredBfss method
     */
    public function test_get_stored_bfss_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredBfss($user->id, 'some_type');

        // Assert
        $this->assertIsNumeric($result);
    }

    /**
     * Test getStoredDiscount method
     */
    public function test_get_stored_discount_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'discount_balance' => 120.75,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredDiscount($user->id);

        // Assert
        $this->assertEquals(120.75, $result);
    }

    /**
     * Test getStoredTree method
     */
    public function test_get_stored_tree_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'tree_balance' => 350.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredTree($user->id);

        // Assert
        $this->assertEquals(350.00, $result);
    }

    /**
     * Test getStoredSms method
     */
    public function test_get_stored_sms_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'sms_balance' => 25,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredSms($user->id);

        // Assert
        $this->assertEquals(25, $result);
    }

    /**
     * Test updateCalculatedHorisental method
     */
    public function test_update_calculated_horisental_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'cash_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->updateCalculatedHorisental(
            $user->id,
            'cash_balance',
            250.00
        );

        // Assert
        $this->assertGreaterThan(0, $result);
        $balance->refresh();
        $this->assertEquals(250.00, $balance->cash_balance);
    }

    /**
     * Test updateBalanceField method
     */
    public function test_update_balance_field_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'share_balance' => 500.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->updateBalanceField(
            $user->id,
            'share_balance',
            750.00
        );

        // Assert
        $this->assertTrue($result);
        $balance->refresh();
        $this->assertEquals(750.00, $balance->share_balance);
    }

    /**
     * Test calculateNewBalance method
     */
    public function test_calculate_new_balance_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'share_balance' => 1000.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $user->id,
            'share_balance',
            250.50
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('record', $result);
        $this->assertArrayHasKey('currentBalance', $result);
        $this->assertArrayHasKey('newBalance', $result);
        $this->assertEquals(1000.00, $result['currentBalance']);
        $this->assertEquals(1250.50, $result['newBalance']);
    }
}
