<?php

namespace Tests\Unit\Services;

use App\Enums\LanguageEnum;
use App\Models\countrie;
use App\Services\CountryService;
use Tests\TestCase;

class CountryServiceTest extends TestCase
{

    protected CountryService $countryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->countryService = new CountryService();
    }

    /**
     * Test updating country language successfully
     */
    public function test_update_country_language_successfully()
    {
        // Arrange
        $country = countrie::factory()->create([
            'langage' => 'English',
            'lang' => LanguageEnum::ENGLISH
        ]);

        // Act
        $result = $this->countryService->updateCountryLanguage($country->id, 'French');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Country language updated successfully', $result['message']);
        $this->assertArrayHasKey('country', $result);
        $this->assertEquals('French', $result['country']->langage);
    }

    /**
     * Test updating country language when country not found
     */
    public function test_update_country_language_when_country_not_found()
    {
        // Act
        $result = $this->countryService->updateCountryLanguage(9999, 'English');

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('Country not found', $result['message']);
    }

    /**
     * Test getting country by ID successfully
     */
    public function test_get_country_by_id_returns_country_when_exists()
    {
        // Arrange
        $country = countrie::factory()->create();

        // Act
        $result = $this->countryService->getCountryById($country->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(countrie::class, $result);
        $this->assertEquals($country->id, $result->id);
    }

    /**
     * Test getting country by ID when not exists
     */
    public function test_get_country_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->countryService->getCountryById(9999);

        // Assert
        $this->assertNull($result);
    }
}
