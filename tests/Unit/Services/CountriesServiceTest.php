<?php

namespace Tests\Unit\Services;

use App\Services\CountriesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountriesServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CountriesService $countriesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->countriesService = new CountriesService();
    }

    /**
     * Test getByPhoneCode returns country when it exists
     */
    public function test_get_by_phone_code_returns_country_when_exists()
    {
        // Arrange
        $country = \App\Models\countrie::factory()->withPhoneCode('961')->create();

        // Act
        $result = $this->countriesService->getByPhoneCode('961');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($country->phonecode, $result->phonecode);
        $this->assertEquals($country->name, $result->name);
    }

    /**
     * Test getByPhoneCode returns null when country does not exist
     */
    public function test_get_by_phone_code_returns_null_when_not_exists()
    {
        // Act
        $result = $this->countriesService->getByPhoneCode('999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getCountryModelByPhoneCode returns country model when it exists
     */
    public function test_get_country_model_by_phone_code_returns_country_when_exists()
    {
        // Arrange
        $country = \App\Models\countrie::factory()->withPhoneCode('961')->create();

        // Act
        $result = $this->countriesService->getCountryModelByPhoneCode('961');

        // Assert
        $this->assertInstanceOf(\App\Models\countrie::class, $result);
        $this->assertEquals($country->id, $result->id);
        $this->assertEquals($country->phonecode, $result->phonecode);
    }

    /**
     * Test getCountryModelByPhoneCode returns null when country does not exist
     */
    public function test_get_country_model_by_phone_code_returns_null_when_not_exists()
    {
        // Act
        $result = $this->countriesService->getCountryModelByPhoneCode('999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAll returns all countries
     */
    public function test_get_all_returns_all_countries()
    {
        // Arrange
        \App\Models\countrie::factory()->count(3)->create();

        // Act
        $result = $this->countriesService->getAll();

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getAll returns empty collection when no countries exist
     */
    public function test_get_all_returns_empty_collection_when_no_countries()
    {
        // Act
        $result = $this->countriesService->getAll();

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test getForDatatable returns countries with default columns
     */
    public function test_get_for_datatable_returns_countries_with_default_columns()
    {
        // Arrange
        \App\Models\countrie::factory()->count(2)->create();

        // Act
        $result = $this->countriesService->getForDatatable();

        // Assert
        $this->assertCount(2, $result);
        $this->assertNotNull($result->first()->id);
        $this->assertNotNull($result->first()->name);
    }

    /**
     * Test getForDatatable returns countries with custom columns
     */
    public function test_get_for_datatable_returns_countries_with_custom_columns()
    {
        // Arrange
        \App\Models\countrie::factory()->count(2)->create();

        // Act
        $result = $this->countriesService->getForDatatable(['id', 'name']);

        // Assert
        $this->assertCount(2, $result);
        $this->assertNotNull($result->first()->id);
        $this->assertNotNull($result->first()->name);
    }
}
