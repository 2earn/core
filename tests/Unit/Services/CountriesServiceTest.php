<?php
namespace Tests\Unit\Services;
use App\Models\countrie;
use App\Services\CountriesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class CountriesServiceTest extends TestCase
{
    use DatabaseTransactions;
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
        $uniqueCode = '99' . rand(100000, 999999);
        $country = countrie::factory()->withPhoneCode($uniqueCode)->create();

        // Act
        $result = $this->countriesService->getByPhoneCode($uniqueCode);

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
        $result = $this->countriesService->getByPhoneCode('999999999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getCountryModelByPhoneCode returns country model when it exists
     */
    public function test_get_country_model_by_phone_code_returns_country_when_exists()
    {
        // Arrange
        $uniqueCode = '88' . rand(100000, 999999);
        $country = countrie::factory()->withPhoneCode($uniqueCode)->create();

        // Act
        $result = $this->countriesService->getCountryModelByPhoneCode($uniqueCode);

        // Assert
        $this->assertNotNull($result, 'Country should not be null');
        $this->assertInstanceOf(countrie::class, $result);
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
        $initialCount = countrie::count();
        countrie::factory()->count(3)->create();
        // Act
        $result = $this->countriesService->getAll();
        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
    }
    /**
     * Test getAll returns empty collection when no countries exist
     */
    public function test_get_all_returns_empty_collection_when_no_countries()
    {
        // Arrange - Delete all countries for this test
        countrie::query()->delete();
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
        $initialCount = countrie::count();
        countrie::factory()->count(2)->create();
        // Act
        $result = $this->countriesService->getForDatatable();
        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 2, $result->count());
        $this->assertNotNull($result->first()->id);
        $this->assertNotNull($result->first()->name);
    }
    /**
     * Test getForDatatable returns countries with custom columns
     */
    public function test_get_for_datatable_returns_countries_with_custom_columns()
    {
        // Arrange
        $initialCount = countrie::count();
        countrie::factory()->count(2)->create();
        // Act
        $result = $this->countriesService->getForDatatable(['id', 'name']);
        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 2, $result->count());
        $this->assertNotNull($result->first()->id);
        $this->assertNotNull($result->first()->name);
    }
}
