<?php

namespace Tests\Unit\Services;

use App\Models\Amount;
use App\Services\AmountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AmountServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AmountService $amountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->amountService = new AmountService();
    }

    /**
     * Test getting amount by ID
     */
    public function test_get_by_id_returns_amount_when_exists()
    {
        // Arrange
        $amount = Amount::factory()->create();

        // Act
        $result = $this->amountService->getById($amount->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Amount::class, $result);
        $this->assertEquals($amount->id, $result->id);
    }

    /**
     * Test getting amount by ID when not exists
     */
    public function test_get_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->amountService->getById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getting paginated amounts without search
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        Amount::factory()->count(15)->create();

        // Act
        $result = $this->amountService->getPaginated(null, 10);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getting paginated amounts with search
     */
    public function test_get_paginated_filters_by_search_term()
    {
        // Arrange
        Amount::factory()->create(['amountsname' => 'Test Amount One']);
        Amount::factory()->create(['amountsname' => 'Test Amount Two']);
        Amount::factory()->create(['amountsname' => 'Other Amount']);

        // Act
        $result = $this->amountService->getPaginated('Test', 10);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /**
     * Test updating an amount successfully
     */
    public function test_update_successfully_updates_amount()
    {
        // Arrange
        $amount = Amount::factory()->create(['amountsname' => 'Original Name']);
        $updateData = ['amountsname' => 'Updated Name'];

        // Act
        $result = $this->amountService->update($amount->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('amounts', [
            'id' => $amount->id,
            'amountsname' => 'Updated Name'
        ]);
    }

    /**
     * Test updating non-existent amount
     */
    public function test_update_returns_false_when_amount_not_found()
    {
        // Act
        $result = $this->amountService->update(9999, ['amountsname' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getting all amounts
     */
    public function test_get_all_returns_all_amounts()
    {
        // Arrange
        Amount::factory()->count(5)->create();

        // Act
        $result = $this->amountService->getAll();

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getting all amounts when none exist
     */
    public function test_get_all_returns_empty_collection_when_no_amounts()
    {
        // Act
        $result = $this->amountService->getAll();

        // Assert
        $this->assertCount(0, $result);
    }
}
