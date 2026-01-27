<?php

namespace Tests\Unit\Services;

use App\Models\BalanceOperation;
use App\Services\BalanceOperationService;
use App\Services\BalanceOperationService;
use Tests\TestCase;

class BalanceOperationServiceTest extends TestCase
{

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
        BalanceOperation::create([
            'operation' => 'Test Op 1',
            'io' => 1,
            'source' => 'test',
        ]);
        BalanceOperation::create([
            'operation' => 'Test Op 2',
            'io' => 2,
            'source' => 'test',
        ]);

        // Act
        $result = $this->balanceOperationService->getAll();

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test getAll returns empty collection when no operations
     */
    public function test_get_all_returns_empty_collection_when_no_operations()
    {
        // Act
        $result = $this->balanceOperationService->getAll();

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test findById returns balance operation when exists
     */
    public function test_find_by_id_returns_balance_operation_when_exists()
    {
        // Arrange
        $operation = BalanceOperation::create([
            'operation' => 'Test Operation',
            'io' => 1,
            'source' => 'test',
        ]);

        // Act
        $result = $this->balanceOperationService->findById($operation->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals($operation->id, $result->id);
        $this->assertEquals('Test Operation', $result->operation);
    }

    /**
     * Test findById returns null when not exists
     */
    public function test_find_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->balanceOperationService->findById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test findByIdOrFail returns balance operation when exists
     */
    public function test_find_by_id_or_fail_returns_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::create([
            'operation' => 'Test Operation',
            'io' => 1,
            'source' => 'test',
        ]);

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
        $this->balanceOperationService->findByIdOrFail(9999);
    }

    /**
     * Test update successfully updates balance operation
     */
    public function test_update_successfully_updates_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::create([
            'operation' => 'Original Operation',
            'io' => 1,
            'source' => 'test',
        ]);

        // Act
        $result = $this->balanceOperationService->update($operation->id, [
            'operation' => 'Updated Operation',
            'io' => 2,
        ]);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Updated Operation', $result->operation);
        $this->assertEquals(2, $result->io);
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
        $result = $this->balanceOperationService->update(9999, [
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
            'operation' => 'New Operation',
            'io' => 1,
            'source' => 'test_source',
            'mode' => 'test_mode',
        ];

        // Act
        $result = $this->balanceOperationService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals('New Operation', $result->operation);
        $this->assertEquals(1, $result->io);
        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'New Operation',
            'source' => 'test_source',
        ]);
    }

    /**
     * Test delete successfully deletes balance operation
     */
    public function test_delete_successfully_deletes_balance_operation()
    {
        // Arrange
        $operation = BalanceOperation::create([
            'operation' => 'Test Operation',
            'io' => 1,
            'source' => 'test',
        ]);

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
        $result = $this->balanceOperationService->delete(9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getMultiplicator returns correct value
     */
    public function test_get_multiplicator_returns_value()
    {
        // Arrange
        $operation = BalanceOperation::create([
            'operation' => 'Test Operation',
            'io' => 1,
            'source' => 'test',
        ]);

        // Act
        $result = $this->balanceOperationService->getMultiplicator($operation->id);

        // Assert
        $this->assertIsInt($result);
    }
}
