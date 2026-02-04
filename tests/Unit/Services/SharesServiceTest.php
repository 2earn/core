<?php

namespace Tests\Unit\Services;

use App\Models\SharesBalances;
use App\Models\User;
use App\Services\SharesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SharesServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SharesService $sharesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sharesService = new SharesService();
    }

    /**
     * Test getSharesData method returns paginated shares for a user
     */
    public function test_get_shares_data_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->count(5)->create([
            'beneficiary_id' => $user->idUser,
        ]);

        // Act
        $result = $this->sharesService->getSharesData($user->idUser);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(5, $result->total());
        $this->assertCount(5, $result->items());
    }

    /**
     * Test getSharesData with search filter
     */
    public function test_get_shares_data_with_search_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'value' => 100,
        ]);
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'value' => 200,
        ]);

        // Act
        $result = $this->sharesService->getSharesData($user->idUser, '100');

        // Assert
        $this->assertNotNull($result);
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getSharesData with sorting
     */
    public function test_get_shares_data_with_sorting_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->count(3)->create([
            'beneficiary_id' => $user->idUser,
        ]);

        // Act
        $result = $this->sharesService->getSharesData(
            $user->idUser,
            null,
            'created_at',
            'asc'
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->total());
    }

    /**
     * Test getSharesData with pagination
     */
    public function test_get_shares_data_with_pagination_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->count(15)->create([
            'beneficiary_id' => $user->idUser,
        ]);

        // Act
        $result = $this->sharesService->getSharesData(
            $user->idUser,
            null,
            'id',
            'desc',
            5
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals(15, $result->total());
        $this->assertEquals(5, $result->perPage());
        $this->assertCount(5, $result->items());
    }

    /**
     * Test getUserSoldSharesValue method returns correct sum
     */
    public function test_get_user_sold_shares_value_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 44,
            'value' => 100.50,
        ]);
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 44,
            'value' => 200.75,
        ]);
        // Different operation - should not be counted
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 45,
            'value' => 50.00,
        ]);

        // Act
        $result = $this->sharesService->getUserSoldSharesValue($user->idUser, 44);

        // Assert
        $this->assertEquals(301.25, $result);
    }

    /**
     * Test getUserSoldSharesValue returns zero when no shares
     */
    public function test_get_user_sold_shares_value_returns_zero_when_no_shares()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->sharesService->getUserSoldSharesValue($user->idUser);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getUserTotalPaid method returns correct sum
     */
    public function test_get_user_total_paid_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 44,
            'total_amount' => 1000.00,
        ]);
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 44,
            'total_amount' => 2500.50,
        ]);
        // Different operation - should not be counted
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 45,
            'total_amount' => 500.00,
        ]);

        // Act
        $result = $this->sharesService->getUserTotalPaid($user->idUser, 44);

        // Assert
        $this->assertEquals(3500.50, $result);
    }

    /**
     * Test getUserTotalPaid returns zero when no shares
     */
    public function test_get_user_total_paid_returns_zero_when_no_shares()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->sharesService->getUserTotalPaid($user->idUser);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getUserTotalPaid with custom balance operation ID
     */
    public function test_get_user_total_paid_with_custom_operation_id()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 50,
            'total_amount' => 750.25,
        ]);

        // Act
        $result = $this->sharesService->getUserTotalPaid($user->idUser, 50);

        // Assert
        $this->assertEquals(750.25, $result);
    }

    /**
     * Test getUserSoldSharesValue with custom balance operation ID
     */
    public function test_get_user_sold_shares_value_with_custom_operation_id()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 50,
            'value' => 150.75,
        ]);

        // Act
        $result = $this->sharesService->getUserSoldSharesValue($user->idUser, 50);

        // Assert
        $this->assertEquals(150.75, $result);
    }
}
