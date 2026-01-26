<?php

namespace Tests\Unit\Services;

use App\Services\TranslaleModelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslaleModelServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TranslaleModelService $translaleModelService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translaleModelService = new TranslaleModelService();
    }

    /**
     * Test getByName returns translation when exists
     */
    public function test_get_by_name_returns_translation_when_exists()
    {
        // Arrange
        $translation = \App\Models\TranslaleModel::factory()->create(['name' => 'test-translation']);

        // Act
        $result = $this->translaleModelService->getByName('test-translation');

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\TranslaleModel::class, $result);
        $this->assertEquals('test-translation', $result->name);
    }

    /**
     * Test getByName returns null when not exists
     */
    public function test_get_by_name_returns_null_when_not_exists()
    {
        // Act
        $result = $this->translaleModelService->getByName('non-existent');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getTranslateName returns formatted name
     */
    public function test_get_translate_name_returns_formatted_name()
    {
        // Arrange
        $model = \App\Models\User::factory()->create();

        // Act
        $result = $this->translaleModelService->getTranslateName($model, 'name');

        // Assert
        $this->assertIsString($result);
        $this->assertStringContainsString('User', $result);
        $this->assertStringContainsString('name', $result);
    }

    /**
     * Test updateOrCreate creates new translation
     */
    public function test_update_or_create_creates_new_translation()
    {
        // Arrange
        $translations = [
            'ar' => 'نص عربي',
            'en' => 'English text',
            'fr' => 'Texte français',
        ];

        // Act
        $result = $this->translaleModelService->updateOrCreate('new-translation', $translations);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\TranslaleModel::class, $result);
        $this->assertEquals('new-translation', $result->name);
        $this->assertEquals('نص عربي', $result->value);
        $this->assertEquals('English text', $result->valueEn);
    }

    /**
     * Test updateOrCreate updates existing translation
     */
    public function test_update_or_create_updates_existing_translation()
    {
        // Arrange
        \App\Models\TranslaleModel::factory()->create([
            'name' => 'existing-translation',
            'value' => 'Old text',
        ]);
        $translations = ['ar' => 'New text'];

        // Act
        $result = $this->translaleModelService->updateOrCreate('existing-translation', $translations);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New text', $result->value);
        $this->assertDatabaseHas('translale_models', [
            'name' => 'existing-translation',
            'value' => 'New text',
        ]);
    }

    /**
     * Test getTranslationsArray returns array of all translations
     */
    public function test_get_translations_array_returns_all_translations()
    {
        // Arrange
        $trans = \App\Models\TranslaleModel::factory()->create([
            'value' => 'Arabic',
            'valueEn' => 'English',
            'valueFr' => 'French',
        ]);

        // Act
        $result = $this->translaleModelService->getTranslationsArray($trans);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('ar', $result);
        $this->assertArrayHasKey('en', $result);
        $this->assertArrayHasKey('fr', $result);
        $this->assertEquals('Arabic', $result['ar']);
        $this->assertEquals('English', $result['en']);
    }

    /**
     * Test prepareTranslationsWithFallback uses provided translations
     */
    public function test_prepare_translations_with_fallback_uses_provided_translations()
    {
        // Arrange
        $translations = ['ar' => 'Arabic text', 'en' => 'English text'];
        $fallback = 'Fallback';

        // Act
        $result = $this->translaleModelService->prepareTranslationsWithFallback($translations, $fallback);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('Arabic text', $result['ar']);
        $this->assertEquals('English text', $result['en']);
    }

    /**
     * Test prepareTranslationsWithFallback uses fallback for missing translations
     */
    public function test_prepare_translations_with_fallback_uses_fallback_for_missing()
    {
        // Arrange
        $translations = ['ar' => 'Arabic text'];
        $fallback = 'Fallback';

        // Act
        $result = $this->translaleModelService->prepareTranslationsWithFallback($translations, $fallback);

        // Assert
        $this->assertStringContainsString('Fallback', $result['en']);
        $this->assertStringContainsString('Fallback', $result['fr']);
    }

    /**
     * Test updateTranslation updates all language fields
     */
    public function test_update_translation_updates_all_fields()
    {
        // Arrange
        $trans = \App\Models\TranslaleModel::factory()->create();
        $title = 'New Title';

        // Act
        $result = $this->translaleModelService->updateTranslation($trans, $title);

        // Assert
        $this->assertTrue($result);
        $trans->refresh();
        $this->assertStringContainsString($title, $trans->value);
        $this->assertStringContainsString($title, $trans->valueEn);
        $this->assertStringContainsString($title, $trans->valueFr);
    }

    /**
     * Test getTranslation returns translation for model
     */
    public function test_get_translation_returns_translation()
    {
        // Arrange
        $model = \App\Models\User::factory()->create();
        $translationName = 'User-name-' . $model->id;
        \App\Models\TranslaleModel::factory()->create([
            'name' => $translationName,
            'value' => 'Arabic Name',
            'valueEn' => 'English Name',
        ]);

        // Act
        $result = $this->translaleModelService->getTranslation($model, 'name', 'Fallback');

        // Assert
        $this->assertIsString($result);
    }
}
