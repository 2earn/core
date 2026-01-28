<?php

namespace Tests\Unit\Services;

use App\Services\UserCurrentBalanceHorisontalService;
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
     * Test getStoredUserBalances returns full balance record
     */
    public function test_get_stored_user_balances_returns_full_record()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredUserBalances($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\UserCurrentBalanceHorisontal::class, $result);
        $this->assertEquals($balance->id, $result->id);
    }

    /**
     * Test getStoredUserBalances returns specific field value
     */
    public function test_get_stored_user_balances_returns_specific_field()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'cash_balance' => 500.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredUserBalances($user->id, 'cash_balance');

        // Assert
        $this->assertEquals(500.00, $result);
    }

    /**
     * Test getStoredCash returns cash balance
     */
    public function test_get_stored_cash_returns_cash_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'cash_balance' => 750.50,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredCash($user->id);

        // Assert
        $this->assertEquals(750.50, $result);
    }

    /**
     * Test getStoredBfss returns BFSS balance by type
     */
    public function test_get_stored_bfss_returns_balance_by_type()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'bfss_balance' => [
                ['type' => 'type1', 'value' => 100.00],
                ['type' => 'type2', 'value' => 200.00],
            ],
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredBfss($user->id, 'type1');

        // Assert
        $this->assertEquals(100.00, $result);
    }

    /**
     * Test getStoredBfss returns zero when type not found
     */
    public function test_get_stored_bfss_returns_zero_when_type_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredBfss($user->id, 'nonexistent');

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getStoredDiscount returns discount balance
     */
    public function test_get_stored_discount_returns_discount_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'discount_balance' => 300.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredDiscount($user->id);

        // Assert
        $this->assertEquals(300.00, $result);
    }

    /**
     * Test getStoredTree returns tree balance
     */
    public function test_get_stored_tree_returns_tree_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'tree_balance' => 150.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredTree($user->id);

        // Assert
        $this->assertEquals(150.00, $result);
    }

    /**
     * Test getStoredSms returns SMS balance
     */
    public function test_get_stored_sms_returns_sms_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'sms_balance' => 50.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->getStoredSms($user->id);

        // Assert
        $this->assertEquals(50.00, $result);
    }

    /**
     * Test updateCalculatedHorisental updates balance field
     */
    public function test_update_calculated_horisental_updates_field()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'cash_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->updateCalculatedHorisental(
            $user->id,
            'cash_balance',
            500.00
        );

        // Assert
        $this->assertGreaterThan(0, $result);
        $balance->refresh();
        $this->assertEquals(500.00, $balance->cash_balance);
    }

    /**
     * Test updateBalanceField updates specific balance field
     */
    public function test_update_balance_field_updates_field()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'share_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->updateBalanceField(
            $user->id,
            'share_balance',
            250.00
        );

        // Assert
        $this->assertTrue($result);
        $balance->refresh();
        $this->assertEquals(250.00, $balance->share_balance);
    }

    /**
     * Test updateBalanceField returns false when user not found
     */
    public function test_update_balance_field_returns_false_when_user_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceHorisontalService->updateBalanceField(
            $user->id,
            'share_balance',
            250.00
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test calculateNewBalance calculates new balance correctly
     */
    public function test_calculate_new_balance_calculates_correctly()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'share_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $user->id,
            'share_balance',
            50.00
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('record', $result);
        $this->assertArrayHasKey('currentBalance', $result);
        $this->assertArrayHasKey('newBalance', $result);
        $this->assertEquals(100.00, $result['currentBalance']);
        $this->assertEquals(150.00, $result['newBalance']);
    }

    /**
     * Test calculateNewBalance handles negative changes
     */
    public function test_calculate_new_balance_handles_negative_change()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceHorisontal::factory()->create([
            'user_id' => $user->id,
            'share_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $user->id,
            'share_balance',
            -30.00
        );

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(100.00, $result['currentBalance']);
        $this->assertEquals(70.00, $result['newBalance']);
    }

    /**
     * Test calculateNewBalance returns null when user not found
     */
    public function test_calculate_new_balance_returns_null_when_user_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceHorisontalService->calculateNewBalance(
            $user->id,
            'share_balance',
            50.00
        );

        // Assert
        $this->assertNull($result);
    }
}
