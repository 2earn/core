<?php

namespace Tests\Unit\Services\BusinessSector;

use App\Models\BusinessSector;
use App\Services\BusinessSector\BusinessSectorService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BusinessSectorServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected BusinessSectorService $businessSectorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->businessSectorService = new BusinessSectorService();
    }

    /**
     * Test getAll returns all business sectors
     */
    public function test_get_all_returns_all_business_sectors()
    {
        // Arrange
        BusinessSector::factory()->count(3)->create();

        // Act
        $result = $this->businessSectorService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getAllOrderedByName orders sectors by name
     */
    public function test_get_all_ordered_by_name_orders_correctly()
    {
        // Arrange
        BusinessSector::factory()->create(['name' => 'Zebra Sector']);
        BusinessSector::factory()->create(['name' => 'Alpha Sector']);
        BusinessSector::factory()->create(['name' => 'Beta Sector']);

        // Act
        $result = $this->businessSectorService->getAllOrderedByName('asc');

        // Assert
        $this->assertEquals('Alpha Sector', $result->first()->name);
    }

    /**
     * Test getAllOrderedByName with desc direction
     */
    public function test_get_all_ordered_by_name_desc()
    {
        // Arrange
        BusinessSector::factory()->create(['name' => 'Zebra Sector']);
        BusinessSector::factory()->create(['name' => 'Alpha Sector']);

        // Act
        $result = $this->businessSectorService->getAllOrderedByName('desc');

        // Assert
        $this->assertEquals('Zebra Sector', $result->first()->name);
    }

    /**
     * Test getBusinessSectors with search filter
     */
    public function test_get_business_sectors_filters_by_search()
    {
        // Arrange
        BusinessSector::factory()->create(['name' => 'Technology Sector']);
        BusinessSector::factory()->create(['name' => 'Healthcare Sector']);

        // Act
        $result = $this->businessSectorService->getBusinessSectors(['search' => 'Technology']);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Technology Sector', $result->first()->name);
    }

    /**
     * Test getBusinessSectors with pagination
     */
    public function test_get_business_sectors_returns_paginated_results()
    {
        // Arrange
        BusinessSector::factory()->count(15)->create();

        // Act
        $result = $this->businessSectorService->getBusinessSectors(['PAGE_SIZE' => 10]);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
    }

    /**
     * Test getById returns business sector
     */
    public function test_get_by_id_returns_business_sector()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->getById($sector->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($sector->id, $result->id);
    }

    /**
     * Test getById returns null when not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->businessSectorService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getBusinessSectorWithImages loads relationships
     */
    public function test_get_business_sector_with_images_loads_relationships()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->getBusinessSectorWithImages($sector->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('logoImage'));
        $this->assertTrue($result->relationLoaded('thumbnailsImage'));
        $this->assertTrue($result->relationLoaded('thumbnailsHomeImage'));
    }

    /**
     * Test getSectorsWithUserPurchases returns sectors with user purchases
     */
    public function test_get_sectors_with_user_purchases_works()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->businessSectorService->getSectorsWithUserPurchases($user->id);

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test findOrFail returns business sector
     */
    public function test_find_or_fail_returns_business_sector()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->findOrFail($sector->id);

        // Assert
        $this->assertEquals($sector->id, $result->id);
    }

    /**
     * Test findOrFail throws exception when not found
     */
    public function test_find_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->businessSectorService->findOrFail(99999);
    }

    /**
     * Test create creates new business sector
     */
    public function test_create_creates_new_business_sector()
    {
        // Arrange
        $data = [
            'name' => 'New Sector',
            'description' => 'Test description',
        ];

        // Act
        $result = $this->businessSectorService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New Sector', $result->name);
        $this->assertDatabaseHas('business_sectors', ['name' => 'New Sector']);
    }

    /**
     * Test update updates business sector
     */
    public function test_update_updates_business_sector()
    {
        // Arrange
        $sector = BusinessSector::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'Updated Name'];

        // Act
        $result = $this->businessSectorService->update($sector->id, $data);

        // Assert
        $this->assertTrue($result);
        $sector->refresh();
        $this->assertEquals('Updated Name', $sector->name);
    }

    /**
     * Test update returns false when not found
     */
    public function test_update_returns_false_when_not_found()
    {
        // Act
        $result = $this->businessSectorService->update(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete deletes business sector
     */
    public function test_delete_deletes_business_sector()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->delete($sector->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('business_sectors', ['id' => $sector->id]);
    }

    /**
     * Test delete returns false when not found
     */
    public function test_delete_returns_false_when_not_found()
    {
        // Act
        $result = $this->businessSectorService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteBusinessSector successfully deletes a business sector (alias method)
     */
    public function test_delete_business_sector_deletes_successfully()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->deleteBusinessSector($sector->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('business_sectors', [
            'id' => $sector->id,
        ]);
    }
}
