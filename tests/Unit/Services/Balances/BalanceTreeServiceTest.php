<?php

namespace Tests\Unit\Services\Balances;

use App\Services\Balances\BalanceTreeService;
use Tests\TestCase;

class BalanceTreeServiceTest extends TestCase
{

    protected BalanceTreeService $balanceTreeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceTreeService = new BalanceTreeService();
    }

    /**
     * Test getTreeUserDatatables method
     * TODO: Implement actual test logic
     */
    public function test_get_tree_user_datatables_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getTreeUserDatatables();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getTreeUserDatatables not yet implemented');
    }

    /**
     * Test getUserBalancesList returns user balances
     */
    public function test_get_user_balances_list_returns_balances()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $balanceAmount = \App\Enums\BalanceEnum::TREE->value;

        // Act
        $result = $this->balanceTreeService->getUserBalancesList($user->idUser, $balanceAmount);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}
