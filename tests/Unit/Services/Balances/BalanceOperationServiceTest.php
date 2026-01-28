<?php

namespace Tests\Unit\Services\Balances;

use App\Services\Balances\BalanceOperationService;
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
     * Test getFilteredOperations method returns paginated results
     */
    public function test_get_filtered_operations_returns_paginated_results()
    {
        // Arrange
        \App\Models\BalanceOperation::factory()->count(15)->create();

        // Act
        $result = $this->balanceOperationService->getFilteredOperations(null, 10);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getFilteredOperations filters by search term
     */
    public function test_get_filtered_operations_filters_by_search()
    {
        // Arrange
        \App\Models\BalanceOperation::factory()->create(['operation' => 'Test Operation One']);
        \App\Models\BalanceOperation::factory()->create(['operation' => 'Test Operation Two']);
        \App\Models\BalanceOperation::factory()->create(['operation' => 'Other Operation']);

        // Act
        $result = $this->balanceOperationService->getFilteredOperations('Test', 10);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /**
     * Test getOperationById returns operation when exists
     */
    public function test_get_operation_by_id_returns_operation_when_exists()
    {
        // Arrange
        $operation = \App\Models\BalanceOperation::factory()->create();

        // Act
        $result = $this->balanceOperationService->getOperationById($operation->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\BalanceOperation::class, $result);
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
        $operation1 = \App\Models\BalanceOperation::factory()->create();
        $operation2 = \App\Models\BalanceOperation::factory()->create();
        $operation3 = \App\Models\BalanceOperation::factory()->create();

        // Act
        $result = $this->balanceOperationService->getAllOperations();

        // Assert
        $this->assertCount(3, $result);
        $this->assertEquals($operation3->id, $result->first()->id); // Most recent first
        $this->assertEquals($operation1->id, $result->last()->id); // Oldest last
    }

    /**
     * Test createOperation creates a new operation successfully
     */
    public function test_create_operation_creates_new_operation()
    {
        // Arrange
        $data = [
            'operation' => 'Test Operation',
            'io' => 'I',
            'source' => 'test',
            'mode' => 'manual',
            'note' => 'Test note',
        ];

        // Act
        $result = $this->balanceOperationService->createOperation($data);

        // Assert
        $this->assertInstanceOf(\App\Models\BalanceOperation::class, $result);
        $this->assertEquals('Test Operation', $result->operation);
        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'Test Operation',
            'source' => 'test',
            'mode' => 'manual',
        ]);
    }

    /**
     * Test updateOperation updates an existing operation successfully
     */
    public function test_update_operation_updates_successfully()
    {
        // Arrange
        $operation = \App\Models\BalanceOperation::factory()->create([
            'operation' => 'Original Operation'
        ]);
        $updateData = [
            'operation' => 'Updated Operation',
            'note' => 'Updated note',
        ];

        // Act
        $result = $this->balanceOperationService->updateOperation($operation->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('balance_operations', [
            'id' => $operation->id,
            'operation' => 'Updated Operation',
            'note' => 'Updated note',
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
     * Test deleteOperation method
     * TODO: Implement actual test logic
     */
    public function test_delete_operation_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->deleteOperation();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for deleteOperation not yet implemented');
    }

    /**
     * Test getOperationCategoryName returns category name when exists
     */
    public function test_get_operation_category_name_returns_name_when_exists()
    {
        // Arrange
        $category = \App\Models\OperationCategory::create([
            'name' => 'Test Category',
            'code' => 'TEST',
        ]);

        // Act
        $result = $this->balanceOperationService->getOperationCategoryName($category->id);

        // Assert
        $this->assertEquals('Test Category', $result);
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
