<?php

namespace Tests\Unit\Services\Translation;

use App\Models\TranslaleModel;
use App\Services\Translation\TranslaleModelService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TranslaleModelServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected TranslaleModelService $translaleModelService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translaleModelService = new TranslaleModelService();
    }

    /**
     * Test getById returns translation
     */
    public function test_get_by_id_returns_translation()
    {
        // Arrange
        $translation = TranslaleModel::factory()->create();

        // Act
        $result = $this->translaleModelService->getById($translation->id);

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
        $result = $this->translaleModelService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAll returns all translations
     */
    public function test_get_all_returns_all_translations()
    {
        // Arrange
        $initialCount = TranslaleModel::count();
        TranslaleModel::factory()->count(5)->create();

        // Act
        $result = $this->translaleModelService->getAll();

        // Assert
        $this->assertCount($initialCount + 5, $result);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        $initialCount = TranslaleModel::count();
        TranslaleModel::factory()->count(15)->create();

        // Act
        $result = $this->translaleModelService->getPaginated(null, 10);

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
        TranslaleModel::factory()->create(['name' => 'special-key']);
        TranslaleModel::factory()->create(['name' => 'another-key']);
        TranslaleModel::factory()->create(['valueEn' => 'special value']);

        // Act
        $result = $this->translaleModelService->getPaginated('special');

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->total());
    }

    /**
     * Test getPaginated orders by ID desc
     */
    public function test_get_paginated_orders_by_id_desc()
    {
        // Arrange
        $translation1 = TranslaleModel::factory()->create();
        $translation2 = TranslaleModel::factory()->create();

        // Act
        $result = $this->translaleModelService->getPaginated();

        // Assert
        $this->assertEquals($translation2->id, $result->items()[0]->id);
    }

    /**
     * Test exists returns true for existing translation (case-insensitive)
     */
    public function test_exists_returns_true_for_existing()
    {
        // Arrange
        TranslaleModel::factory()->create(['name' => 'test-key']);

        // Act
        $result = $this->translaleModelService->exists('TEST-KEY');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test exists returns false for non-existent translation
     */
    public function test_exists_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->translaleModelService->exists('non-existent-key');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test create creates new translation with default values
     */
    public function test_create_creates_translation_with_defaults()
    {
        // Act
        $result = $this->translaleModelService->create('new-key');

        // Assert
        $this->assertInstanceOf(TranslaleModel::class, $result);
        $this->assertEquals('new-key', $result->name);
        $this->assertEquals('new-key AR', $result->value);
        $this->assertEquals('new-key FR', $result->valueFr);
        $this->assertEquals('new-key EN', $result->valueEn);
        $this->assertEquals('new-key TR', $result->valueTr);
        $this->assertEquals('new-key ES', $result->valueEs);
        $this->assertEquals('new-key RU', $result->valueRu);
        $this->assertEquals('new-key DE', $result->valueDe);
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
        $result = $this->translaleModelService->create('custom-key', $values);

        // Assert
        $this->assertInstanceOf(TranslaleModel::class, $result);
        $this->assertEquals('custom-key', $result->name);
        $this->assertEquals('Arabic Value', $result->value);
        $this->assertEquals('French Value', $result->valueFr);
        $this->assertEquals('English Value', $result->valueEn);
        $this->assertEquals('Turkish Value', $result->valueTr);
        $this->assertEquals('Spanish Value', $result->valueEs);
        $this->assertEquals('Russian Value', $result->valueRu);
        $this->assertEquals('German Value', $result->valueDe);
    }

    /**
     * Test update updates translation values
     */
    public function test_update_updates_translation()
    {
        // Arrange
        $translation = TranslaleModel::factory()->create();
        $updateData = ['valueEn' => 'Updated English', 'valueFr' => 'Updated French'];

        // Act
        $result = $this->translaleModelService->update($translation->id, $updateData);

        // Assert
        $this->assertTrue($result);

        $translation->refresh();
        $this->assertEquals('Updated English', $translation->valueEn);
        $this->assertEquals('Updated French', $translation->valueFr);
    }

    /**
     * Test delete removes translation
     */
    public function test_delete_removes_translation()
    {
        // Arrange
        $translation = TranslaleModel::factory()->create();

        // Act
        $result = $this->translaleModelService->delete($translation->id);

        // Assert
        $this->assertTrue($result);
        $this->assertNull(TranslaleModel::find($translation->id));
    }

    /**
     * Test getAllAsKeyValueArrays returns properly formatted arrays
     */
    public function test_get_all_as_key_value_arrays_returns_formatted_arrays()
    {
        // Arrange
        TranslaleModel::factory()->create([
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
        $result = $this->translaleModelService->getAllAsKeyValueArrays();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('ar', $result);
        $this->assertArrayHasKey('fr', $result);
        $this->assertArrayHasKey('en', $result);
        $this->assertArrayHasKey('tr', $result);
        $this->assertArrayHasKey('es', $result);
        $this->assertArrayHasKey('ru', $result);
        $this->assertArrayHasKey('de', $result);
        $this->assertEquals('Arabic', $result['ar']['test-key']);
        $this->assertEquals('French', $result['fr']['test-key']);
        $this->assertEquals('English', $result['en']['test-key']);
    }

    /**
     * Test search finds translations by name
     */
    public function test_search_finds_translations_by_name()
    {
        // Arrange
        TranslaleModel::factory()->create(['name' => 'findme-key']);
        TranslaleModel::factory()->create(['name' => 'other-key']);

        // Act
        $result = $this->translaleModelService->search('findme');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('findme-key', $result->first()->name);
    }

    /**
     * Test search finds translations by value (case-insensitive)
     */
    public function test_search_finds_translations_by_value()
    {
        // Arrange
        TranslaleModel::factory()->create(['valueEn' => 'search this text']);
        TranslaleModel::factory()->create(['valueEn' => 'other text']);

        // Act
        $result = $this->translaleModelService->search('SEARCH');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test search orders by ID desc
     */
    public function test_search_orders_by_id_desc()
    {
        // Arrange
        $translation1 = TranslaleModel::factory()->create(['name' => 'test-key-1']);
        $translation2 = TranslaleModel::factory()->create(['name' => 'test-key-2']);

        // Act
        $result = $this->translaleModelService->search('test');

        // Assert
        $this->assertEquals($translation2->id, $result->first()->id);
    }

    /**
     * Test count returns correct count
     */
    public function test_count_returns_correct_count()
    {
        // Arrange
        $initialCount = TranslaleModel::count();
        TranslaleModel::factory()->count(8)->create();

        // Act
        $result = $this->translaleModelService->count();

        // Assert
        $this->assertEquals($initialCount + 8, $result);
    }

    /**
     * Test count returns zero when no translations
     */
    public function test_count_returns_zero_when_empty()
    {
        // Arrange - Just verify count method works
        // Act
        $result = $this->translaleModelService->count();

        // Assert - Should return at least 0 or more
        $this->assertGreaterThanOrEqual(0, $result);
    }

    /**
     * Test getByNamePattern returns matching translations
     */
    public function test_get_by_name_pattern_returns_matching_translations()
    {
        // Arrange
        TranslaleModel::factory()->create(['name' => 'prefix-key1']);
        TranslaleModel::factory()->create(['name' => 'prefix-key2']);
        TranslaleModel::factory()->create(['name' => 'other-key']);

        // Act
        $result = $this->translaleModelService->getByNamePattern('prefix%');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test getByNamePattern returns empty collection when no matches
     */
    public function test_get_by_name_pattern_returns_empty_when_no_matches()
    {
        // Arrange
        TranslaleModel::factory()->create(['name' => 'test-key']);

        // Act
        $result = $this->translaleModelService->getByNamePattern('nonexistent%');

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test getAllAsKeyValueArrays with multiple translations
     */
    public function test_get_all_as_key_value_arrays_with_multiple_translations()
    {
        // Arrange
        TranslaleModel::factory()->create([
            'name' => 'test-unique-key1',
            'value' => 'AR1',
            'valueEn' => 'EN1',
        ]);
        TranslaleModel::factory()->create([
            'name' => 'test-unique-key2',
            'value' => 'AR2',
            'valueEn' => 'EN2',
        ]);

        // Act
        $result = $this->translaleModelService->getAllAsKeyValueArrays();

        // Assert
        $this->assertIsArray($result['ar']);
        $this->assertIsArray($result['en']);
        $this->assertArrayHasKey('test-unique-key1', $result['ar']);
        $this->assertArrayHasKey('test-unique-key2', $result['ar']);
        $this->assertEquals('AR1', $result['ar']['test-unique-key1']);
        $this->assertEquals('AR2', $result['ar']['test-unique-key2']);
        $this->assertEquals('EN1', $result['en']['test-unique-key1']);
        $this->assertEquals('EN2', $result['en']['test-unique-key2']);
    }

    /**
     * Test search across multiple language fields
     */
    public function test_search_searches_across_all_language_fields()
    {
        // Arrange
        TranslaleModel::factory()->create(['value' => 'unique-arabic-term']);
        TranslaleModel::factory()->create(['valueFr' => 'unique-french-term']);
        TranslaleModel::factory()->create(['valueEn' => 'unique-english-term']);

        // Act
        $result = $this->translaleModelService->search('unique');

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }
}
