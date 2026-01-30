<?php

namespace Tests\Unit\Services\Balances;

use App\Models\OperationCategory;
use App\Services\Balances\OperationCategoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OperationCategoryServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected OperationCategoryService $operationCategoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->operationCategoryService = new OperationCategoryService();
    }

    /**
     * Test getFilteredCategories returns paginated results
     */
    public function test_get_filtered_categories_returns_paginated_results()
    {
        // Arrange
        OperationCategory::factory()->count(15)->create();

        // Act
        $result = $this->operationCategoryService->getFilteredCategories(null, 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
    }

    /**
     * Test getFilteredCategories filters by search term in name
     */
    public function test_get_filtered_categories_filters_by_name()
    {
        // Arrange
        OperationCategory::factory()->create(['name' => 'Special Category']);
        OperationCategory::factory()->create(['name' => 'Regular Category']);

        // Act
        $result = $this->operationCategoryService->getFilteredCategories('Special');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getFilteredCategories filters by search term in code
     */
    public function test_get_filtered_categories_filters_by_code()
    {
        // Arrange
        OperationCategory::factory()->create(['code' => 'SPEC001']);
        OperationCategory::factory()->create(['code' => 'REG002']);

        // Act
        $result = $this->operationCategoryService->getFilteredCategories('SPEC');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getFilteredCategories orders by ID desc
     */
    public function test_get_filtered_categories_orders_by_id_desc()
    {
        // Arrange
        $category1 = OperationCategory::factory()->create();
        $category2 = OperationCategory::factory()->create();

        // Act
        $result = $this->operationCategoryService->getFilteredCategories();

        // Assert
        $this->assertEquals($category2->id, $result->first()->id);
    }

    /**
     * Test getCategoryById returns category
     */
    public function test_get_category_by_id_returns_category()
    {
        // Arrange
        $category = OperationCategory::factory()->create();

        // Act
        $result = $this->operationCategoryService->getCategoryById($category->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
    }

    /**
     * Test getCategoryById returns null for non-existent
     */
    public function test_get_category_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->operationCategoryService->getCategoryById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAllCategories returns all categories
     */
    public function test_get_all_categories_returns_all_categories()
    {
        // Arrange
        $initialCount = OperationCategory::count();
        OperationCategory::factory()->count(5)->create();

        // Act
        $result = $this->operationCategoryService->getAllCategories();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 5, $result->count());
    }

    /**
     * Test getAllCategories orders by ID desc
     */
    public function test_get_all_categories_orders_by_id_desc()
    {
        // Arrange
        $category1 = OperationCategory::factory()->create();
        $category2 = OperationCategory::factory()->create();

        // Act
        $result = $this->operationCategoryService->getAllCategories();

        // Assert
        $this->assertEquals($category2->id, $result->first()->id);
    }

    /**
     * Test getAll returns all categories
     */
    public function test_get_all_returns_all_categories()
    {
        // Arrange
        $initialCount = OperationCategory::count();
        OperationCategory::factory()->count(3)->create();

        // Act
        $result = $this->operationCategoryService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
    }

    /**
     * Test createCategory creates new category
     */
    public function test_create_category_creates_new_category()
    {
        // Arrange
        $uniqueName = 'Test Category ' . time();
        $uniqueCode = 'TEST' . time();
        $data = [
            'name' => $uniqueName,
            'code' => $uniqueCode,
            'description' => 'Test description',
        ];

        // Act
        $result = $this->operationCategoryService->createCategory($data);

        // Assert
        $this->assertInstanceOf(OperationCategory::class, $result);
        $this->assertEquals($uniqueName, $result->name);
        $this->assertDatabaseHas('operation_categories', ['name' => $uniqueName]);
    }

    /**
     * Test updateCategory updates category
     */
    public function test_update_category_updates_category()
    {
        // Arrange
        $category = OperationCategory::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->operationCategoryService->updateCategory($category->id, $data);

        // Assert
        $this->assertTrue($result);
        $category->refresh();
        $this->assertEquals('New Name', $category->name);
    }

    /**
     * Test updateCategory returns false for non-existent
     */
    public function test_update_category_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->operationCategoryService->updateCategory(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteCategory deletes category
     */
    public function test_delete_category_deletes_category()
    {
        // Arrange
        $category = OperationCategory::factory()->create();

        // Act
        $result = $this->operationCategoryService->deleteCategory($category->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('operation_categories', ['id' => $category->id]);
    }

    /**
     * Test deleteCategory returns false for non-existent
     */
    public function test_delete_category_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->operationCategoryService->deleteCategory(99999);

        // Assert
        $this->assertFalse($result);
    }
}
