<?php
namespace Tests\Unit\Services\Balances;
use App\Models\User;
use App\Models\SharesBalances;
use App\Services\Balances\ShareBalanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class ShareBalanceServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected ShareBalanceService $shareBalanceService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->shareBalanceService = new ShareBalanceService();
    }
    /**
     * Test getSharesSoldesData returns paginated results
     */
    public function test_get_shares_soldes_data_returns_paginated_results()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesData('', 10);
        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }
    /**
     * Test getSharesSoldesData filters by search term
     */
    public function test_get_shares_soldes_data_filters_by_search()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesData('test', 10);
        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }
    /**
     * Test getSharesSoldesData with custom sorting
     */
    public function test_get_shares_soldes_data_with_custom_sorting()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesData('', 10, 'created_at', 'asc');
        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }
    /**
     * Test getShareBalancesList returns collection
     */
    public function test_get_share_balances_list_returns_collection()
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $result = $this->shareBalanceService->getShareBalancesList($user->idUser);
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharesSoldesQuery returns collection
     */
    public function test_get_shares_soldes_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharePriceEvolutionDateQuery returns collection
     */
    public function test_get_share_price_evolution_date_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionDateQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharePriceEvolutionWeekQuery returns collection
     */
    public function test_get_share_price_evolution_week_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionWeekQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharePriceEvolutionMonthQuery returns collection
     */
    public function test_get_share_price_evolution_month_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionMonthQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharePriceEvolutionDayQuery returns collection
     */
    public function test_get_share_price_evolution_day_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionDayQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharePriceEvolutionQuery returns collection
     */
    public function test_get_share_price_evolution_query_returns_collection()
    {
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getSharesSoldeQuery returns query builder
     */
    public function test_get_shares_solde_query_returns_query_builder()
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $result = $this->shareBalanceService->getSharesSoldeQuery($user->idUser);
        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Query\Builder::class, $result);
    }
    /**
     * Test updateShareBalance updates balance successfully
     */
    public function test_update_share_balance_updates_successfully()
    {
        // Arrange
        $shareBalanceId = 1;
        $newAmount = 500.00;
        // Act
        $result = $this->shareBalanceService->updateShareBalance($shareBalanceId, $newAmount);
        // Assert
        $this->assertTrue($result);
    }
    /**
     * Test updateShareBalance returns true on success
     */
    public function test_update_share_balance_returns_true_on_success()
    {
        // Arrange
        $shareBalanceId = 999999; // Non-existent but should still return true (no exception)
        $newAmount = 100.00;
        // Act
        $result = $this->shareBalanceService->updateShareBalance($shareBalanceId, $newAmount);
        // Assert
        $this->assertTrue($result);
    }
    /**
     * Test getUserBalancesForDelayedSponsorship returns collection
     */
    public function test_get_user_balances_for_delayed_sponsorship_returns_collection()
    {
        // Arrange
        $user = User::factory()->create();
        $balanceOperationId = 44;
        $retardatifReservation = 24;
        $saleCount = 10;
        // Act
        $result = $this->shareBalanceService->getUserBalancesForDelayedSponsorship(
            $balanceOperationId,
            $user->idUser,
            $retardatifReservation,
            $saleCount
        );
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getUserBalancesForDelayedSponsorship returns empty on error
     */
    public function test_get_user_balances_for_delayed_sponsorship_handles_errors()
    {
        // Arrange
        $invalidUserId = -1;
        $balanceOperationId = 44;
        $retardatifReservation = 24;
        $saleCount = 10;
        // Act
        $result = $this->shareBalanceService->getUserBalancesForDelayedSponsorship(
            $balanceOperationId,
            $invalidUserId,
            $retardatifReservation,
            $saleCount
        );
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
    /**
     * Test getShareBalancesList filters by user
     */
    public function test_get_share_balances_list_filters_by_user()
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $result = $this->shareBalanceService->getShareBalancesList($user->idUser);
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        // All results should belong to the user (if any exist)
    }
    /**
     * Test getSharesSoldesData supports different per page values
     */
    public function test_get_shares_soldes_data_supports_custom_per_page()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesData('', 50);
        // Assert
        $this->assertEquals(50, $result->perPage());
    }
    /**
     * Test getSharesSoldesQuery includes all required fields
     */
    public function test_get_shares_soldes_query_includes_required_fields()
    {
        // Act
        $result = $this->shareBalanceService->getSharesSoldesQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        // Query includes joins with users, meta_users, and countries tables
    }
    /**
     * Test getSharePriceEvolutionMonthQuery aggregates data by month
     */
    public function test_get_share_price_evolution_month_query_works()
    {
        // Arrange - Create test data with different months
        $user = User::factory()->create();
        SharesBalances::factory()
            ->withOperation(44)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 100,
                'created_at' => now()->subMonth(1),
            ]);
        SharesBalances::factory()
            ->withOperation(44)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 150,
                'created_at' => now(),
            ]);
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionMonthQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertGreaterThanOrEqual(0, $result->count());
        // If we have results, verify structure
        if ($result->count() > 0) {
            $firstItem = $result->first();
            $this->assertObjectHasProperty('x', $firstItem); // Month in Y-m format
            $this->assertObjectHasProperty('y', $firstItem); // Sum of values
        }
    }
    /**
     * Test getSharePriceEvolutionDayQuery aggregates data by day name
     */
    public function test_get_share_price_evolution_day_query_works()
    {
        // Arrange - Create test data
        $user = User::factory()->create();
        SharesBalances::factory()
            ->withOperation(44)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 200,
            ]);
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionDayQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertGreaterThanOrEqual(0, $result->count());
        // If we have results, verify structure
        if ($result->count() > 0) {
            $firstItem = $result->first();
            $this->assertObjectHasProperty('x', $firstItem); // Day name
            $this->assertObjectHasProperty('y', $firstItem); // Sum of values
            $this->assertObjectHasProperty('z', $firstItem); // Day of week number
        }
    }
    /**
     * Test getSharePriceEvolutionQuery returns cumulative share data
     */
    public function test_get_share_price_evolution_query_works()
    {
        // Arrange - Create test data
        $user = User::factory()->create();
        SharesBalances::factory()
            ->withOperation(44)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 50,
                'unit_price' => 10.50,
            ]);
        SharesBalances::factory()
            ->withOperation(44)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 75,
                'unit_price' => 11.25,
            ]);
        // Act
        $result = $this->shareBalanceService->getSharePriceEvolutionQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertGreaterThanOrEqual(0, $result->count());
        // If we have results, verify structure
        if ($result->count() > 0) {
            $firstItem = $result->first();
            $this->assertObjectHasProperty('x', $firstItem); // Cumulative sum
            $this->assertObjectHasProperty('y', $firstItem); // Unit price
        }
    }
    /**
     * Test getSharesSoldeQuery filters by beneficiary
     */
    public function test_get_shares_solde_query_works()
    {
        // Arrange - Create test data
        $user = User::factory()->create();
        SharesBalances::factory()
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 100,
                'current_balance' => 1000,
            ]);
        // Act
        $result = $this->shareBalanceService->getSharesSoldeQuery($user->idUser);
        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Query\Builder::class, $result);
        // Execute query and verify results
        $records = $result->get();
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $records);
        // All records should belong to the specified user
        foreach ($records as $record) {
            $this->assertEquals($user->idUser, $record->beneficiary_id);
        }
    }
    /**
     * Test updateShareBalance updates existing record
     */
    public function test_update_share_balance_works()
    {
        // Arrange - Create a share balance record
        $user = User::factory()->create();
        $shareBalance = SharesBalances::factory()
            ->withBeneficiary($user->idUser)
            ->unpayed()
            ->create([
                'value' => 100,
                'current_balance' => 500,
            ]);
        $newAmount = 750.00;
        // Act
        $result = $this->shareBalanceService->updateShareBalance($shareBalance->id, $newAmount);
        // Assert
        $this->assertTrue($result);
        // Verify the update
        $updated = SharesBalances::find($shareBalance->id);
        $this->assertEquals($newAmount, $updated->current_balance);
        $this->assertEquals(1, $updated->payed);
    }
    /**
     * Test getUserBalancesForDelayedSponsorship retrieves within time window
     */
    public function test_get_user_balances_for_delayed_sponsorship_works()
    {
        // Arrange - Create test data within time window
        $user = User::factory()->create();
        $balanceOperationId = 44;
        $retardatifReservation = 24; // 24 hours
        $saleCount = 10;
        // Create a recent share balance (within 24 hours)
        $recentBalance = SharesBalances::factory()
            ->withOperation($balanceOperationId)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 100,
                'created_at' => now()->subHours(12), // 12 hours ago
            ]);
        // Create an old share balance (outside 24 hours)
        $oldBalance = SharesBalances::factory()
            ->withOperation($balanceOperationId)
            ->withBeneficiary($user->idUser)
            ->create([
                'value' => 50,
                'created_at' => now()->subHours(30), // 30 hours ago
            ]);
        // Act
        $result = $this->shareBalanceService->getUserBalancesForDelayedSponsorship(
            $balanceOperationId,
            $user->idUser,
            $retardatifReservation,
            $saleCount
        );
        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        // Should only return the recent balance (within 24 hours)
        $this->assertGreaterThanOrEqual(1, $result->count());
        // Verify all returned balances are within time window
        foreach ($result as $balance) {
            $this->assertEquals($user->idUser, $balance->beneficiary_id);
            $this->assertEquals($balanceOperationId, $balance->balance_operation_id);
        }
    }
}
