<?php

namespace Tests\Unit\Services\Partner;

use App\Models\BusinessSector;
use App\Models\Partner;
use App\Services\Partner\PartnerService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PartnerServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PartnerService $partnerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partnerService = new PartnerService();
    }

    /**
     * Test getAllPartners returns all partners ordered by created_at DESC
     */
    public function test_get_all_partners_returns_all_partners()
    {
        // Arrange
        $countBefore = Partner::count();
        Partner::factory()->count(3)->create();

        // Act
        $result = $this->partnerService->getAllPartners();

        // Assert
        $this->assertGreaterThanOrEqual($countBefore + 3, $result->count());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getAllPartners returns empty collection when no partners exist
     */
    public function test_get_all_partners_returns_empty_collection_when_no_partners()
    {
        // Arrange - Delete all partners in this transaction
        Partner::query()->delete();

        // Act
        $result = $this->partnerService->getAllPartners();

        // Assert
        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getAllPartners orders by created_at DESC
     */
    public function test_get_all_partners_orders_by_created_at_desc()
    {
        // Arrange
        $partner1 = Partner::factory()->create(['created_at' => now()->subDays(2)]);
        $partner2 = Partner::factory()->create(['created_at' => now()->subDay()]);
        $partner3 = Partner::factory()->create(['created_at' => now()]);

        // Act
        $result = $this->partnerService->getAllPartners();

        // Assert
        $this->assertEquals($partner3->id, $result->first()->id);
        $this->assertEquals($partner1->id, $result->last()->id);
    }

    /**
     * Test getPartnerById returns correct partner
     */
    public function test_get_partner_by_id_returns_partner()
    {
        // Arrange
        $partner = Partner::factory()->create();

        // Act
        $result = $this->partnerService->getPartnerById($partner->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($partner->id, $result->id);
        $this->assertEquals($partner->company_name, $result->company_name);
    }

    /**
     * Test getPartnerById returns null when partner not found
     */
    public function test_get_partner_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->partnerService->getPartnerById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test createPartner creates new partner successfully
     */
    public function test_create_partner_creates_new_partner()
    {
        // Arrange
        $data = [
            'company_name' => 'Test Company',
            'platform_url' => 'https://test.com',
            'platform_description' => 'Test description',
            'partnership_reason' => 'Test reason',
        ];

        // Act
        $result = $this->partnerService->createPartner($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Partner::class, $result);
        $this->assertEquals('Test Company', $result->company_name);
        $this->assertEquals('https://test.com', $result->platform_url);
        $this->assertDatabaseHas('partners', ['company_name' => 'Test Company']);
    }

    /**
     * Test createPartner with business sector
     */
    public function test_create_partner_with_business_sector()
    {
        // Arrange
        $businessSector = BusinessSector::factory()->create();
        $data = [
            'company_name' => 'Test Company',
            'business_sector_id' => $businessSector->id,
            'platform_url' => 'https://test.com',
            'platform_description' => 'Test description',
            'partnership_reason' => 'Test reason',
        ];

        // Act
        $result = $this->partnerService->createPartner($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($businessSector->id, $result->business_sector_id);
    }

    /**
     * Test updatePartner updates partner successfully
     */
    public function test_update_partner_updates_partner()
    {
        // Arrange
        $partner = Partner::factory()->create(['company_name' => 'Old Name']);
        $data = ['company_name' => 'New Name'];

        // Act
        $result = $this->partnerService->updatePartner($partner->id, $data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New Name', $result->company_name);
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'company_name' => 'New Name',
        ]);
    }

    /**
     * Test updatePartner returns null when partner not found
     */
    public function test_update_partner_returns_null_when_not_found()
    {
        // Act
        $result = $this->partnerService->updatePartner(99999, ['company_name' => 'Test']);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test deletePartner deletes partner successfully
     */
    public function test_delete_partner_deletes_partner()
    {
        // Arrange
        $partner = Partner::factory()->create();

        // Act
        $result = $this->partnerService->deletePartner($partner->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }

    /**
     * Test deletePartner returns false when partner not found
     */
    public function test_delete_partner_returns_false_when_not_found()
    {
        // Act
        $result = $this->partnerService->deletePartner(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getFilteredPartners returns paginated results
     */
    public function test_get_filtered_partners_returns_paginated_results()
    {
        // Arrange
        $countBefore = Partner::count();
        Partner::factory()->count(20)->create();

        // Act
        $result = $this->partnerService->getFilteredPartners('', 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual($countBefore + 20, $result->total());
    }

    /**
     * Test getFilteredPartners filters by company name
     */
    public function test_get_filtered_partners_filters_by_company_name()
    {
        // Arrange
        Partner::factory()->create(['company_name' => 'ABC Company']);
        Partner::factory()->create(['company_name' => 'XYZ Company']);
        Partner::factory()->create(['company_name' => 'DEF Company']);

        // Act
        $result = $this->partnerService->getFilteredPartners('ABC');

        // Assert
        $this->assertEquals(1, $result->total());
        $this->assertEquals('ABC Company', $result->first()->company_name);
    }

    /**
     * Test getFilteredPartners filters by platform URL
     */
    public function test_get_filtered_partners_filters_by_platform_url()
    {
        // Arrange
        Partner::factory()->create(['platform_url' => 'https://example.com']);
        Partner::factory()->create(['platform_url' => 'https://test.com']);

        // Act
        $result = $this->partnerService->getFilteredPartners('example');

        // Assert
        $this->assertEquals(1, $result->total());
        $items = $result->items();
        $this->assertStringContainsString('example', $items[0]->platform_url);
    }

    /**
     * Test getFilteredPartners filters by business sector name
     */
    public function test_get_filtered_partners_filters_by_business_sector_name()
    {
        // Arrange
        $sector1 = BusinessSector::factory()->create(['name' => 'Technology']);
        $sector2 = BusinessSector::factory()->create(['name' => 'Healthcare']);
        Partner::factory()->create(['business_sector_id' => $sector1->id]);
        Partner::factory()->create(['business_sector_id' => $sector2->id]);

        // Act
        $result = $this->partnerService->getFilteredPartners('Technology');

        // Assert
        $this->assertEquals(1, $result->total());
    }

    /**
     * Test getFilteredPartners loads businessSector relationship
     */
    public function test_get_filtered_partners_loads_business_sector_relationship()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();
        Partner::factory()->create(['business_sector_id' => $sector->id]);

        // Act
        $result = $this->partnerService->getFilteredPartners();

        // Assert
        $this->assertTrue($result->items()[0]->relationLoaded('businessSector'));
    }

    /**
     * Test getPartnersByBusinessSector returns partners for specific sector
     */
    public function test_get_partners_by_business_sector_returns_partners()
    {
        // Arrange
        $sector1 = BusinessSector::factory()->create();
        $sector2 = BusinessSector::factory()->create();
        Partner::factory()->count(2)->create(['business_sector_id' => $sector1->id]);
        Partner::factory()->create(['business_sector_id' => $sector2->id]);

        // Act
        $result = $this->partnerService->getPartnersByBusinessSector($sector1->id);

        // Assert
        $this->assertCount(2, $result);
        $this->assertTrue($result->first()->relationLoaded('businessSector'));
    }

    /**
     * Test getPartnersByBusinessSector returns empty collection when no partners
     */
    public function test_get_partners_by_business_sector_returns_empty_collection_when_no_partners()
    {
        // Arrange
        $sector = BusinessSector::factory()->create();

        // Act
        $result = $this->partnerService->getPartnersByBusinessSector($sector->id);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test searchPartnersByCompanyName finds partners by name
     */
    public function test_search_partners_by_company_name_finds_partners()
    {
        // Arrange
        Partner::factory()->create(['company_name' => 'Alpha Solutions']);
        Partner::factory()->create(['company_name' => 'Beta Corporation']);
        Partner::factory()->create(['company_name' => 'Alpha Industries']);

        // Act
        $result = $this->partnerService->searchPartnersByCompanyName('Alpha');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test searchPartnersByCompanyName is case insensitive
     */
    public function test_search_partners_by_company_name_is_case_insensitive()
    {
        // Arrange
        Partner::factory()->create(['company_name' => 'Test Company']);

        // Act
        $result = $this->partnerService->searchPartnersByCompanyName('test');

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * Test searchPartnersByCompanyName returns empty collection when no matches
     */
    public function test_search_partners_by_company_name_returns_empty_collection_when_no_matches()
    {
        // Arrange
        Partner::factory()->create(['company_name' => 'Test Company']);

        // Act
        $result = $this->partnerService->searchPartnersByCompanyName('NonExistent');

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test searchPartnersByCompanyName performs partial matching
     */
    public function test_search_partners_by_company_name_performs_partial_matching()
    {
        // Arrange
        Partner::factory()->create(['company_name' => 'Technology Solutions Inc']);

        // Act
        $result = $this->partnerService->searchPartnersByCompanyName('Solutions');

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('Solutions', $result->first()->company_name);
    }
}
