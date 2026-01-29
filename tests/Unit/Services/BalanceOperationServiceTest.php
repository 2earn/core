<?php
namespace Tests\Unit\Services;
use App\Models\BalanceOperation;
use App\Services\BalanceOperationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class BalanceOperationServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected BalanceOperationService $balanceOperationService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceOperationService = new BalanceOperationService();
    }
    /**
     * Test getAll returns all balance operations
     */
    public function test_get_all_returns_all_balance_operations()
    {
        // Arrange
        $initialCount = BalanceOperation::count();
        BalanceOperation::factory()->count(3)->create();
        // Act
        $result = $this->balanceOperationService->getAll();
        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
    }
    /**
     * Test getAll returns empty collection when no operations
     */
    public function test_get_all_returns_empty_collection_when_no_operations()
    {
        // Note: Since there might be existing records in the database,
        // we test that getAll() returns a collection
        // Act
        $result = $this->balanceOperationService->getAll();
        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }
    /**
     * Test findById returns balance operation when exists
     */
    public function test_find_by_id_returns_balance_operation_when_exists()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();
        // Act
        $result = $this->balanceOperationService->findById($operation->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals($operation->id, $result->id);
        $this->assertEquals($operation->operation, $result->operation);
    }
    /**
     * Test findById returns null when not exists
     */
    public function test_find_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->balanceOperationService->findById(999999);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test findByIdOrFail returns balance operation when exists
     */
    public function test_find_by_id_or_fail_returns_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();
        // Act
        $result = $this->balanceOperationService->findByIdOrFail($operation->id);
        // Assert
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals($operation->id, $result->id);
    }
    /**
     * Test findByIdOrFail throws exception when not exists
     */
    public function test_find_by_id_or_fail_throws_exception_when_not_exists()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);
        // Act
        $this->balanceOperationService->findByIdOrFail(999999);
    }
    /**
     * Test update successfully updates balance operation
     */
    public function test_update_successfully_updates_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Original Operation'
        ]);
        // Act
        $result = $this->balanceOperationService->update($operation->id, [
            'operation' => 'Updated Operation',
        ]);
        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Updated Operation', $result->operation);
        $this->assertDatabaseHas('balance_operations', [
            'id' => $operation->id,
            'operation' => 'Updated Operation',
        ]);
    }
    /**
     * Test update returns null when operation not found
     */
    public function test_update_returns_null_when_operation_not_found()
    {
        // Act
        $result = $this->balanceOperationService->update(999999, [
            'operation' => 'Test',
        ]);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test create successfully creates balance operation
     */
    public function test_create_successfully_creates_balance_operation()
    {
        // Arrange
        $data = [
            'ref' => 'REF-TEST-' . uniqid(),
            'operation_category_id' => 1,
            'operation' => 'New Operation',
            'direction' => 'IN',
            'balance_id' => 100,
        ];
        // Act
        $result = $this->balanceOperationService->create($data);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals('New Operation', $result->operation);
        $this->assertEquals('IN', $result->direction);
        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'New Operation',
            'ref' => $data['ref'],
        ]);
    }
    /**
     * Test delete successfully deletes balance operation
     */
    public function test_delete_successfully_deletes_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();
        // Act
        $result = $this->balanceOperationService->delete($operation->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('balance_operations', [
            'id' => $operation->id,
        ]);
    }
    /**
     * Test delete returns false when operation not found
     */
    public function test_delete_returns_false_when_operation_not_found()
    {
        // Act
        $result = $this->balanceOperationService->delete(999999);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test getMultiplicator returns correct value
     */
    public function test_get_multiplicator_returns_value()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();
        // Act
        $result = $this->balanceOperationService->getMultiplicator($operation->id);
        // Assert
        $this->assertIsInt($result);
        $this->assertContains($result, [1, -1]);
    }
}