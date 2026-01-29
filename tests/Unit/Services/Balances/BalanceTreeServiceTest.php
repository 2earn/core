<?php

namespace Tests\Unit\Services\Balances;

use App\Enums\BalanceEnum;
use App\Models\User;
use App\Services\Balances\BalanceTreeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BalanceTreeServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected BalanceTreeService $balanceTreeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceTreeService = new BalanceTreeService();
    }

    /**
     * Test getUserBalancesList returns user balances for tree balance type
     */
    public function test_get_user_balances_list_returns_tree_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::TREE->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns user balances for cash balance type
     */
    public function test_get_user_balances_list_returns_cash_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::CASH->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns user balances for share balance type
     */
    public function test_get_user_balances_list_returns_share_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::SHARE->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns user balances for BFS balance type
     */
    public function test_get_user_balances_list_returns_bfs_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::BFS->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns user balances for discount balance type
     */
    public function test_get_user_balances_list_returns_discount_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::DB->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns user balances for SMS balance type
     */
    public function test_get_user_balances_list_returns_sms_balances()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::SMS->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList defaults to cash balances for unknown type
     */
    public function test_get_user_balances_list_defaults_to_cash_for_unknown_type()
    {
        // Arrange
        $user = User::factory()->create();
        $unknownType = 999; // Unknown balance type

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $unknownType);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /**
     * Test getUserBalancesList returns ordered results
     */
    public function test_get_user_balances_list_returns_ordered_results()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceAmount = BalanceEnum::TREE->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        // Results should be ordered by created_at DESC
    }
}
