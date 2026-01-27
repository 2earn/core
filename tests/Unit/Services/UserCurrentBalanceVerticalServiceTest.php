<?php

namespace Tests\Unit\Services;

use App\Enums\BalanceEnum;
use App\Models\User;
use App\Models\UserCurrentBalanceVertical;
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
     * Test getUserBalanceVertical method
     */
    public function test_get_user_balance_vertical_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => 1,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->getUserBalanceVertical($user->id, 1);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(UserCurrentBalanceVertical::class, $result);
        $this->assertEquals($balance->id, $result->id);
    }

    /**
     * Test updateBalanceAfterOperation method
     */
    public function test_update_balance_after_operation_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => 1,
            'current_balance' => 1000.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateBalanceAfterOperation(
            $user->id,
            1,
            250.50,
            123,
            250.50,
            now()->toDateTimeString()
        );

        // Assert
        $this->assertTrue($result);
        $balance->refresh();
        $this->assertEquals(1250.50, $balance->current_balance);
        $this->assertEquals(1000.00, $balance->previous_balance);
        $this->assertEquals(123, $balance->last_operation_id);
    }

    /**
     * Test updateCalculatedVertical method
     */
    public function test_update_calculated_vertical_works()
    {
        // Arrange
        $user = User::factory()->create();
        $balance = UserCurrentBalanceVertical::factory()->create([
            'user_id' => $user->id,
            'balance_id' => 2,
            'current_balance' => 500.00,
        ]);

        // Act
        $result = $this->userCurrentBalanceVerticalService->updateCalculatedVertical($user->id, 2, 750.00);

        // Assert
        $this->assertGreaterThan(0, $result);
        $balance->refresh();
        $this->assertEquals(750.00, $balance->current_balance);
    }

    /**
     * Test getAllUserBalances method
     */
    public function test_get_all_user_balances_works()
    {
        // Arrange
        $user = User::factory()->create();
        UserCurrentBalanceVertical::factory()->count(5)->create(['user_id' => $user->id]);
        UserCurrentBalanceVertical::factory()->count(3)->create();

        // Act
        $result = $this->userCurrentBalanceVerticalService->getAllUserBalances($user->id);

        // Assert
        $this->assertCount(5, $result);
        $this->assertTrue($result->every(fn($b) => $b->user_id === $user->id));
    }
}
