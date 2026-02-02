<?php

namespace Tests\Unit\Services\Balances;

use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use App\Services\Balances\BalanceOperationService;
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
     * Test getFilteredOperations method returns paginated results
     */
    public function test_get_filtered_operations_returns_paginated_results()
    {
        // Arrange
        $initialCount = BalanceOperation::count();
        BalanceOperation::factory()->count(15)->create();

        // Act
        $result = $this->balanceOperationService->getFilteredOperations(null, 10);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertGreaterThanOrEqual($initialCount + 15, $result->total());
    }

    /**
     * Test getFilteredOperations filters by search term
     */
    public function test_get_filtered_operations_filters_by_search()
    {
        // Arrange
        $uniqueSearchTerm = 'TestUnique' . time();
        BalanceOperation::factory()->create(['operation' => $uniqueSearchTerm . ' Operation One']);
        BalanceOperation::factory()->create(['operation' => $uniqueSearchTerm . ' Operation Two']);
        BalanceOperation::factory()->create(['operation' => 'Other Operation']);

        // Act
        $result = $this->balanceOperationService->getFilteredOperations($uniqueSearchTerm, 10);

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->total());
    }

    /**
     * Test getOperationById returns operation when exists
     */
    public function test_get_operation_by_id_returns_operation_when_exists()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();

        // Act
        $result = $this->balanceOperationService->getOperationById($operation->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals($operation->id, $result->id);
    }

    /**
     * Test getOperationById returns null when not exists
     */
    public function test_get_operation_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->balanceOperationService->getOperationById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAllOperations returns all operations ordered by id desc
     */
    public function test_get_all_operations_returns_all_operations()
    {
        // Arrange
        $initialCount = BalanceOperation::count();
        $operation1 = BalanceOperation::factory()->create();
        $operation2 = BalanceOperation::factory()->create();
        $operation3 = BalanceOperation::factory()->create();

        // Act
        $result = $this->balanceOperationService->getAllOperations();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
        $this->assertEquals($operation3->id, $result->first()->id); // Most recent first
    }

    /**
     * Test createOperation creates a new operation successfully
     */
    public function test_create_operation_creates_new_operation()
    {
        // Arrange
        $data = [
            'operation' => 'Test Operation',
            'direction' => 'IN',
            'balance_id' => 1,
            'ref' => 'REF-' . uniqid(),
            'operation_category_id' => 1,
        ];

        // Act
        $result = $this->balanceOperationService->createOperation($data);

        // Assert
        $this->assertInstanceOf(BalanceOperation::class, $result);
        $this->assertEquals('Test Operation', $result->operation);
        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'Test Operation',
            'direction' => 'IN',
        ]);
    }

    /**
     * Test updateOperation updates an existing operation successfully
     */
    public function test_update_operation_updates_successfully()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Original Operation'
        ]);
        $updateData = [
            'operation' => 'Updated Operation',
            'direction' => 'OUT',
        ];

        // Act
        $result = $this->balanceOperationService->updateOperation($operation->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('balance_operations', [
            'id' => $operation->id,
            'operation' => 'Updated Operation',
            'direction' => 'OUT',
        ]);
    }

    /**
     * Test updateOperation returns false when operation not found
     */
    public function test_update_operation_returns_false_when_not_found()
    {
        // Act
        $result = $this->balanceOperationService->updateOperation(9999, ['operation' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteOperation deletes successfully when operation exists
     */
    public function test_delete_operation_deletes_successfully()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();

        // Act
        $result = $this->balanceOperationService->deleteOperation($operation->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('balance_operations', [
            'id' => $operation->id,
        ]);
    }

    /**
     * Test deleteOperation returns false when operation not found
     */
    public function test_delete_operation_returns_false_when_not_found()
    {
        // Act
        $result = $this->balanceOperationService->deleteOperation(9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getOperationCategoryName returns category name when exists
     */
    public function test_get_operation_category_name_returns_name_when_exists()
    {
        // Arrange
        $uniqueName = 'Test Category ' . time();
        $category = OperationCategory::create([
            'name' => $uniqueName,
            'code' => 'TEST' . time(),
        ]);

        // Act
        $result = $this->balanceOperationService->getOperationCategoryName($category->id);

        // Assert
        $this->assertEquals($uniqueName, $result);
    }

    /**
     * Test getOperationCategoryName returns dash when category not found
     */
    public function test_get_operation_category_name_returns_dash_when_not_found()
    {
        // Act
        $result = $this->balanceOperationService->getOperationCategoryName(9999);

        // Assert
        $this->assertEquals('-', $result);
    }

    /**
     * Test getOperationCategoryName returns dash when null provided
     */
    public function test_get_operation_category_name_returns_dash_when_null()
    {
        // Act
        $result = $this->balanceOperationService->getOperationCategoryName(null);

        // Assert
        $this->assertEquals('-', $result);
    }
}
