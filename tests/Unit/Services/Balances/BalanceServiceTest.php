<?php
namespace Tests\Unit\Services\Balances;
use App\Models\User;
use App\Models\BFSsBalances;
use App\Models\BalanceOperation;
use App\Models\CashBalances;
use App\Models\SmsBalances;
use App\Models\ChanceBalances;
use App\Models\SharesBalances;
use App\Services\Balances\BalanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class BalanceServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected BalanceService $balanceService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceService = new BalanceService();
    }
    /**
     * Test getUserBalancesQuery method returns query builder
     */
    public function test_get_user_balances_query_works()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        CashBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 100,
        ]);
        // Act
        $result = $this->balanceService->getUserBalancesQuery('cash_balances');
        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Query\Builder::class, $result);
        $this->assertNotNull($result->first());
    }
    /**
     * Test getBalanceTableName method returns correct table names
     */
    public function test_get_balance_table_name_works()
    {
        // Test Balance-For-Shopping
        $result = $this->balanceService->getBalanceTableName('Balance-For-Shopping');
        $this->assertEquals('bfss_balances', $result);
        // Test Discounts-Balance
        $result = $this->balanceService->getBalanceTableName('Discounts-Balance');
        $this->assertEquals('discount_balances', $result);
        // Test SMS-Balance
        $result = $this->balanceService->getBalanceTableName('SMS-Balance');
        $this->assertEquals('sms_balances', $result);
        // Test default (cash)
        $result = $this->balanceService->getBalanceTableName('anything-else');
        $this->assertEquals('cash_balances', $result);
    }
    /**
     * Test getUserBalancesDatatables method returns datatables response
     */
    public function test_get_user_balances_datatables_works()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        CashBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 100,
        ]);
        // Act
        $result = $this->balanceService->getUserBalancesDatatables('Cash-Balance');
        // Assert
        $this->assertNotNull($result);
    }
    /**
     * Test getPurchaseBFSUserDatatables method returns datatables response
     */
    public function test_get_purchase_b_f_s_user_datatables_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        BFSsBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 100,
            'percentage' => 10,
        ]);
        // Act
        $result = $this->balanceService->getPurchaseBFSUserDatatables($user->idUser, null);
        // Assert
        $this->assertNotNull($result);
    }
    /**
     * Test getPurchaseBFSUserDatatables with type filter
     */
    public function test_get_purchase_b_f_s_user_datatables_with_type_filter_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        BFSsBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 100,
            'percentage' => 10,
        ]);
        // Act
        $result = $this->balanceService->getPurchaseBFSUserDatatables($user->idUser, 10);
        // Assert
        $this->assertNotNull($result);
    }
    /**
     * Test getSmsUserDatatables method returns datatables response
     */
    public function test_get_sms_user_datatables_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        SmsBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 50,
        ]);
        // Act
        $result = $this->balanceService->getSmsUserDatatables($user->idUser);
        // Assert
        $this->assertNotNull($result);
    }
    /**
     * Test getChanceUserDatatables method returns datatables response
     */
    public function test_get_chance_user_datatables_works()
    {
        // Arrange
        $user = User::factory()->create();
        BalanceOperation::factory()->create(['id' => 1, 'operation' => 'Test', 'direction' => 'IN']);
        ChanceBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'balance_operation_id' => 1,
            'value' => 5,
        ]);
        // Act
        $result = $this->balanceService->getChanceUserDatatables($user->idUser);
        // Assert
        $this->assertNotNull($result);
    }
    /**
     * Test getSharesSoldeDatatables method returns datatables response
     */
    public function test_get_shares_solde_datatables_works()
    {
        // Arrange
        $user = User::factory()->create();
        SharesBalances::factory()->create([
            'beneficiary_id' => $user->idUser,
            'value' => 10,
            'unit_price' => 100.00,
        ]);
        // Act
        $result = $this->balanceService->getSharesSoldeDatatables($user->idUser);
        // Assert
        $this->assertNotNull($result);
    }
}

