<?php

namespace Tests\Unit\Services\BusinessSector;

use App\Services\BusinessSector\BusinessSectorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessSectorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BusinessSectorService $businessSectorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->businessSectorService = new BusinessSectorService();
    }

    /**
     * Test getAll method returns all business sectors
     */
    public function test_get_all_returns_all_business_sectors()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->count(5)->create();

        // Act
        $result = $this->businessSectorService->getAll();

        // Assert
        $this->assertCount(5, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getAllOrderedByName returns sectors ordered by name ascending
     */
    public function test_get_all_ordered_by_name_returns_sectors_ordered_asc()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->create(['name' => 'Zulu']);
        \App\Models\BusinessSector::factory()->create(['name' => 'Alpha']);
        \App\Models\BusinessSector::factory()->create(['name' => 'Bravo']);

        // Act
        $result = $this->businessSectorService->getAllOrderedByName('asc');

        // Assert
        $this->assertEquals('Alpha', $result->first()->name);
        $this->assertEquals('Zulu', $result->last()->name);
    }

    /**
     * Test getAllOrderedByName returns sectors ordered by name descending
     */
    public function test_get_all_ordered_by_name_returns_sectors_ordered_desc()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->create(['name' => 'Zulu']);
        \App\Models\BusinessSector::factory()->create(['name' => 'Alpha']);
        \App\Models\BusinessSector::factory()->create(['name' => 'Bravo']);

        // Act
        $result = $this->businessSectorService->getAllOrderedByName('desc');

        // Assert
        $this->assertEquals('Zulu', $result->first()->name);
        $this->assertEquals('Alpha', $result->last()->name);
    }

    /**
     * Test getBusinessSectors returns all sectors without pagination
     */
    public function test_get_business_sectors_returns_all_without_pagination()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->count(3)->create();

        // Act
        $result = $this->businessSectorService->getBusinessSectors();

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getBusinessSectors returns paginated results
     */
    public function test_get_business_sectors_returns_paginated_results()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->count(15)->create();

        // Act
        $result = $this->businessSectorService->getBusinessSectors(['PAGE_SIZE' => 10]);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getBusinessSectors filters by search term
     */
    public function test_get_business_sectors_filters_by_search()
    {
        // Arrange
        \App\Models\BusinessSector::factory()->create(['name' => 'Technology Sector']);
        \App\Models\BusinessSector::factory()->create(['name' => 'Healthcare Sector']);
        \App\Models\BusinessSector::factory()->create(['description' => 'Technology related']);

        // Act
        $result = $this->businessSectorService->getBusinessSectors(['search' => 'Technology']);

        // Assert
        $this->assertEquals(2, $result->count());
    }

    /**
     * Test getById returns business sector when exists
     */
    public function test_get_by_id_returns_sector_when_exists()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->getById($sector->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\BusinessSector::class, $result);
        $this->assertEquals($sector->id, $result->id);
    }

    /**
     * Test getById returns null when sector not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->businessSectorService->getById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getBusinessSectorWithImages returns sector with images loaded
     */
    public function test_get_business_sector_with_images_returns_sector_with_relations()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->getBusinessSectorWithImages($sector->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\BusinessSector::class, $result);
        $this->assertEquals($sector->id, $result->id);
        $this->assertTrue($result->relationLoaded('logoImage'));
        $this->assertTrue($result->relationLoaded('thumbnailsImage'));
        $this->assertTrue($result->relationLoaded('thumbnailsHomeImage'));
    }

    /**
     * Test getBusinessSectorWithImages returns null when not found
     */
    public function test_get_business_sector_with_images_returns_null_when_not_found()
    {
        // Act
        $result = $this->businessSectorService->getBusinessSectorWithImages(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getSectorsWithUserPurchases returns sectors with user purchases
     */
    public function test_get_sectors_with_user_purchases_returns_sectors()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $sector = \App\Models\BusinessSector::factory()->create();
        $platform = \App\Models\Platform::factory()->create(['business_sector_id' => $sector->id]);
        $item = \App\Models\Item::factory()->create(['platform_id' => $platform->id]);
        $order = \App\Models\Order::factory()->create(['user_id' => $user->id]);
        \App\Models\OrderDetail::factory()->create([
            'order_id' => $order->id,
            'item_id' => $item->id,
        ]);

        // Act
        $result = $this->businessSectorService->getSectorsWithUserPurchases($user->id);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getSectorsWithUserPurchases returns empty collection when no purchases
     */
    public function test_get_sectors_with_user_purchases_returns_empty_when_no_purchases()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->businessSectorService->getSectorsWithUserPurchases($user->id);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test findOrFail returns business sector when exists
     */
    public function test_find_or_fail_returns_sector_when_exists()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->findOrFail($sector->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\BusinessSector::class, $result);
        $this->assertEquals($sector->id, $result->id);
    }

    /**
     * Test findOrFail throws exception when sector not found
     */
    public function test_find_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->businessSectorService->findOrFail(9999);
    }

    /**
     * Test create successfully creates a new business sector
     */
    public function test_create_creates_new_business_sector()
    {
        // Arrange
        $data = [
            'name' => 'New Sector',
            'description' => 'Test description',
            'color' => '#FF5733',
        ];

        // Act
        $result = $this->businessSectorService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\BusinessSector::class, $result);
        $this->assertEquals('New Sector', $result->name);
        $this->assertDatabaseHas('business_sectors', [
            'name' => 'New Sector',
            'description' => 'Test description',
            'color' => '#FF5733',
        ]);
    }

    /**
     * Test update successfully updates a business sector
     */
    public function test_update_updates_business_sector_successfully()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create([
            'name' => 'Original Name',
        ]);
        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ];

        // Act
        $result = $this->businessSectorService->update($sector->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('business_sectors', [
            'id' => $sector->id,
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ]);
    }

    /**
     * Test update returns false when sector not found
     */
    public function test_update_returns_false_when_sector_not_found()
    {
        // Act
        $result = $this->businessSectorService->update(9999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete successfully deletes a business sector
     */
    public function test_delete_deletes_business_sector_successfully()
    {
        // Arrange
        $sector = \App\Models\BusinessSector::factory()->create();

        // Act
        $result = $this->businessSectorService->delete($sector->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('business_sectors', [
            'id' => $sector->id,
        ]);
    }

    /**
     * Test delete returns false when sector not found
     */
    public function test_delete_returns_false_when_sector_not_found()
    {
        // Act
        $result = $this->businessSectorService->delete(9999);

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
