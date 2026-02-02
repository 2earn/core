<?php

namespace Tests\Unit\Services\Translation;

use App\Models\translatetabs;
use App\Services\Translation\TranslateTabsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TranslateTabsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected TranslateTabsService $translateTabsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translateTabsService = new TranslateTabsService();
    }

    /**
     * Test getById returns translation
     */
    public function test_get_by_id_returns_translation()
    {
        // Arrange
        $translation = translatetabs::factory()->create();

        // Act
        $result = $this->translateTabsService->getById($translation->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($translation->id, $result->id);
        $this->assertEquals($translation->name, $result->name);
    }

    /**
     * Test getById returns null for non-existent ID
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->translateTabsService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAll returns all translations
     */
    public function test_get_all_returns_all_translations()
    {
        // Arrange
        $initialCount = translatetabs::count();
        translatetabs::factory()->count(5)->create();

        // Act
        $result = $this->translateTabsService->getAll();

        // Assert
        $this->assertCount($initialCount + 5, $result);
    }

    /**
     * Test getAll orders by ID desc
     */
    public function test_get_all_orders_by_id_desc()
    {
        // Arrange
        $translation1 = translatetabs::factory()->create();
        $translation2 = translatetabs::factory()->create();

        // Act
        $result = $this->translateTabsService->getAll();

        // Assert
        $this->assertEquals($translation2->id, $result->first()->id);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        $initialCount = translatetabs::count();
        translatetabs::factory()->count(15)->create();

        // Act
        $result = $this->translateTabsService->getPaginated(null, 10);

        // Assert
        $this->assertCount(10, $result);
        $this->assertEquals($initialCount + 15, $result->total());
    }

    /**
     * Test getPaginated with search filters results
     */
    public function test_get_paginated_with_search_filters_results()
    {
        // Arrange
        translatetabs::factory()->create(['name' => 'special-key']);
        translatetabs::factory()->create(['name' => 'another-key']);
        translatetabs::factory()->create(['valueEn' => 'special value']);

        // Act
        $result = $this->translateTabsService->getPaginated('special');

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->total());
    }

    /**
     * Test exists returns true for existing translation
     */
    public function test_exists_returns_true_for_existing()
    {
        // Arrange
        $translation = translatetabs::factory()->create(['name' => 'test-key']);

        // Act
        $result = $this->translateTabsService->exists('test-key');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test exists returns false for non-existent translation
     */
    public function test_exists_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->translateTabsService->exists('non-existent-key');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test create creates new translation with default values
     */
    public function test_create_creates_translation_with_defaults()
    {
        // Act
        $result = $this->translateTabsService->create('new-key');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('new-key', $result->name);
        $this->assertEquals('new-key AR', $result->value);
        $this->assertEquals('new-key FR', $result->valueFr);
        $this->assertEquals('new-key EN', $result->valueEn);
    }

    /**
     * Test create creates new translation with custom values
     */
    public function test_create_creates_translation_with_custom_values()
    {
        // Arrange
        $values = [
            'value' => 'Arabic Value',
            'valueFr' => 'French Value',
            'valueEn' => 'English Value',
            'valueTr' => 'Turkish Value',
            'valueEs' => 'Spanish Value',
            'valueRu' => 'Russian Value',
            'valueDe' => 'German Value',
        ];

        // Act
        $result = $this->translateTabsService->create('custom-key', $values);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('custom-key', $result->name);
        $this->assertEquals('Arabic Value', $result->value);
        $this->assertEquals('French Value', $result->valueFr);
        $this->assertEquals('English Value', $result->valueEn);
    }

    /**
     * Test update updates translation values
     */
    public function test_update_updates_translation()
    {
        // Arrange
        $translation = translatetabs::factory()->create();
        $updateData = ['valueEn' => 'Updated English'];

        // Act
        $result = $this->translateTabsService->update($translation->id, $updateData);

        // Assert
        $this->assertTrue($result);

        $translation->refresh();
        $this->assertEquals('Updated English', $translation->valueEn);
    }

    /**
     * Test delete removes translation
     */
    public function test_delete_removes_translation()
    {
        // Arrange
        $translation = translatetabs::factory()->create();

        // Act
        $result = $this->translateTabsService->delete($translation->id);

        // Assert
        $this->assertTrue($result);
        $this->assertNull(translatetabs::find($translation->id));
    }

    /**
     * Test search finds translations by name
     */
    public function test_search_finds_translations_by_name()
    {
        // Arrange
        translatetabs::factory()->create(['name' => 'findme-key']);
        translatetabs::factory()->create(['name' => 'other-key']);

        // Act
        $result = $this->translateTabsService->search('findme');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('findme-key', $result->first()->name);
    }

    /**
     * Test search finds translations by value
     */
    public function test_search_finds_translations_by_value()
    {
        // Arrange
        translatetabs::factory()->create(['valueEn' => 'search this text']);
        translatetabs::factory()->create(['valueEn' => 'other text']);

        // Act
        $result = $this->translateTabsService->search('search');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getAllAsKeyValueArrays returns properly formatted arrays
     */
    public function test_get_all_as_key_value_arrays_returns_formatted_arrays()
    {
        // Arrange
        translatetabs::factory()->create([
            'name' => 'test-key',
            'value' => 'Arabic',
            'valueFr' => 'French',
            'valueEn' => 'English',
            'valueTr' => 'Turkish',
            'valueEs' => 'Spanish',
            'valueRu' => 'Russian',
            'valueDe' => 'German',
        ]);

        // Act
        $result = $this->translateTabsService->getAllAsKeyValueArrays();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('ar', $result);
        $this->assertArrayHasKey('fr', $result);
        $this->assertArrayHasKey('en', $result);
        $this->assertEquals('Arabic', $result['ar']['test-key']);
        $this->assertEquals('French', $result['fr']['test-key']);
        $this->assertEquals('English', $result['en']['test-key']);
    }

    /**
     * Test count returns correct count
     */
    public function test_count_returns_correct_count()
    {
        // Arrange
        $initialCount = translatetabs::count();
        translatetabs::factory()->count(7)->create();

        // Act
        $result = $this->translateTabsService->count();

        // Assert
        $this->assertEquals($initialCount + 7, $result);
    }

    /**
     * Test getByNamePattern returns matching translations
     */
    public function test_get_by_name_pattern_returns_matching_translations()
    {
        // Arrange
        translatetabs::factory()->create(['name' => 'prefix-key1']);
        translatetabs::factory()->create(['name' => 'prefix-key2']);
        translatetabs::factory()->create(['name' => 'other-key']);

        // Act
        $result = $this->translateTabsService->getByNamePattern('prefix%');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test bulkCreate creates multiple translations
     */
    public function test_bulk_create_creates_multiple_translations()
    {
        // Arrange
        $translations = [
            ['name' => 'bulk-key1', 'valueEn' => 'Value 1'],
            ['name' => 'bulk-key2', 'valueEn' => 'Value 2'],
            ['name' => 'bulk-key3', 'valueEn' => 'Value 3'],
        ];

        // Act
        $result = $this->translateTabsService->bulkCreate($translations);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($this->translateTabsService->exists('bulk-key1'));
        $this->assertTrue($this->translateTabsService->exists('bulk-key2'));
        $this->assertTrue($this->translateTabsService->exists('bulk-key3'));
    }

    /**
     * Test bulkCreate skips existing translations
     */
    public function test_bulk_create_skips_existing_translations()
    {
        // Arrange
        translatetabs::factory()->create(['name' => 'existing-key']);

        $translations = [
            ['name' => 'existing-key', 'valueEn' => 'New Value'],
            ['name' => 'new-key', 'valueEn' => 'Value'],
        ];

        // Act
        $result = $this->translateTabsService->bulkCreate($translations);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($this->translateTabsService->exists('new-key'));
    }

    /**
     * Test getStatistics returns correct statistics
     */
    public function test_get_statistics_returns_statistics()
    {
        // Arrange
        $initialCount = translatetabs::count();
        translatetabs::factory()->count(5)->create();

        // Act
        $result = $this->translateTabsService->getStatistics();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_count', $result);
        $this->assertArrayHasKey('today_count', $result);
        $this->assertArrayHasKey('this_week_count', $result);
        $this->assertArrayHasKey('this_month_count', $result);
        $this->assertEquals($initialCount + 5, $result['total_count']);
    }
}
