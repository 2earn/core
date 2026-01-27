<?php

namespace Tests\Unit\Services;

use App\Enums\BalanceEnum;
use App\Services\UserCurrentBalanceVerticalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCurrentBalanceVerticalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserCurrentBalanceVerticalService $userCurrentBalanceVerticalService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userCurrentBalanceVerticalService = new UserCurrentBalanceVerticalService();
    }

    /**
     * Test getUserBalanceVertical returns balance record
     */
    public function test_get_user_balance_vertical_returns_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => BalanceEnum::CASH->value,
            'current_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->getUserBalanceVertical($user->id, BalanceEnum::CASH);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\UserCurrentBalanceVertical::class, $result);
        $this->assertEquals($balance->id, $result->id);
        $this->assertEquals(100.00, $result->current_balance);
    }

    /**
     * Test getUserBalanceVertical returns null when not found
     */
    public function test_get_user_balance_vertical_returns_null_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceVerticalService->getUserBalanceVertical($user->id, BalanceEnum::CASH);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getUserBalanceVertical works with integer balance ID
     */
    public function test_get_user_balance_vertical_works_with_integer()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => 1,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->getUserBalanceVertical($user->id, 1);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($balance->id, $result->id);
    }

    /**
     * Test updateBalanceAfterOperation updates balance correctly
     */
    public function test_update_balance_after_operation_updates_balance()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => BalanceEnum::CASH->value,
            'current_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            $user->id,
            BalanceEnum::CASH,
            50.00,
            123,
            50.00,
            now()->toDateTimeString()
        );

        // Assert
        $this->assertTrue($result);
        $balance->refresh();
        $this->assertEquals(150.00, $balance->current_balance);
        $this->assertEquals(100.00, $balance->previous_balance);
        $this->assertEquals(123, $balance->last_operation_id);
        $this->assertEquals(50.00, $balance->last_operation_value);
    }

    /**
     * Test updateBalanceAfterOperation handles negative balance changes
     */
    public function test_update_balance_after_operation_handles_negative_change()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => BalanceEnum::CASH->value,
            'current_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            $user->id,
            BalanceEnum::CASH,
            -30.00,
            124,
            -30.00,
            now()->toDateTimeString()
        );

        // Assert
        $this->assertTrue($result);
        $balance->refresh();
        $this->assertEquals(70.00, $balance->current_balance);
        $this->assertEquals(100.00, $balance->previous_balance);
    }

    /**
     * Test updateBalanceAfterOperation returns false when balance not found
     */
    public function test_update_balance_after_operation_returns_false_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            $user->id,
            BalanceEnum::CASH,
            50.00,
            123,
            50.00,
            now()->toDateTimeString()
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test updateCalculatedVertical updates balance value
     */
    public function test_update_calculated_vertical_updates_value()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balance = \App\Models\UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => BalanceEnum::CASH->value,
            'current_balance' => 100.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateCalculatedVertical(
            $user->id,
            BalanceEnum::CASH,
            250.00
        );

        // Assert
        $this->assertGreaterThan(0, $result);
        $balance->refresh();
        $this->assertEquals(250.00, $balance->current_balance);
    }

    /**
     * Test updateCalculatedVertical returns zero when balance not found
     */
    public function test_update_calculated_vertical_returns_zero_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateCalculatedVertical(
            $user->id,
            BalanceEnum::CASH,
            250.00
        );

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getAllUserBalances returns all user balances
     */
    public function test_get_all_user_balances_returns_all_balances()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserCurrentBalanceVertical::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->getAllUserBalances($user->id);

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getAllUserBalances returns empty collection when no balances
     */
    public function test_get_all_user_balances_returns_empty_when_no_balances()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userCurrentBalanceVerticalService->getAllUserBalances($user->id);

        // Assert
        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }
}
