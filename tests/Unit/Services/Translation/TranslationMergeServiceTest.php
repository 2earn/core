<?php

namespace Tests\Unit\Services\Translation;

use App\Services\Translation\TranslationMergeService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TranslationMergeServiceTest extends TestCase
{
    protected TranslationMergeService $translationMergeService;
    protected string $testSourcePath;
    protected string $testTargetPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->translationMergeService = new TranslationMergeService();

        // Create test directories
        $this->testSourcePath = storage_path('framework/testing/source.json');
        $this->testTargetPath = resource_path('lang/test.json');

        // Ensure parent directories exist
        File::ensureDirectoryExists(dirname($this->testSourcePath));
        File::ensureDirectoryExists(dirname($this->testTargetPath));
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (File::exists($this->testSourcePath)) {
            File::delete($this->testSourcePath);
        }
        if (File::exists($this->testTargetPath)) {
            File::delete($this->testTargetPath);
        }

        // Clean up backup files
        $backupFiles = File::glob($this->testTargetPath . '.backup_*');
        foreach ($backupFiles as $file) {
            File::delete($file);
        }

        parent::tearDown();
    }

    /**
     * Test mergeTranslations successfully merges translations
     */
    public function test_merge_translations_successfully_merges()
    {
        // Arrange
        $sourceData = ['key1' => 'value1', 'key2' => 'value2'];
        $targetData = ['key3' => 'value3', 'key4' => 'value4'];

        File::put($this->testSourcePath, json_encode($sourceData));
        File::put($this->testTargetPath, json_encode($targetData));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['currentCount']);
        $this->assertEquals(2, $result['newCount']);
        $this->assertEquals(4, $result['mergedCount']);
        $this->assertArrayHasKey('backupPath', $result);
        $this->assertArrayHasKey('sample', $result);

        // Verify merged content
        $mergedContent = json_decode(File::get($this->testTargetPath), true);
        $this->assertCount(4, $mergedContent);
        $this->assertEquals('value1', $mergedContent['key1']);
        $this->assertEquals('value3', $mergedContent['key3']);
    }

    /**
     * Test mergeTranslations overwrites existing keys with new values
     */
    public function test_merge_translations_overwrites_existing_keys()
    {
        // Arrange
        $sourceData = ['key1' => 'new_value1', 'key2' => 'value2'];
        $targetData = ['key1' => 'old_value1', 'key3' => 'value3'];

        File::put($this->testSourcePath, json_encode($sourceData));
        File::put($this->testTargetPath, json_encode($targetData));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);

        // Verify the key was overwritten
        $mergedContent = json_decode(File::get($this->testTargetPath), true);
        $this->assertEquals('new_value1', $mergedContent['key1']);
    }

    /**
     * Test mergeTranslations fails when source file doesn't exist
     */
    public function test_merge_translations_fails_with_nonexistent_source()
    {
        // Arrange
        $nonExistentPath = storage_path('framework/testing/nonexistent.json');

        // Act
        $result = $this->translationMergeService->mergeTranslations($nonExistentPath, 'test');

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Source file not found', $result['message']);
    }

    /**
     * Test mergeTranslations creates target file if it doesn't exist
     */
    public function test_merge_translations_creates_target_if_not_exists()
    {
        // Arrange
        $sourceData = ['key1' => 'value1', 'key2' => 'value2'];
        File::put($this->testSourcePath, json_encode($sourceData));

        // Ensure target doesn't exist
        if (File::exists($this->testTargetPath)) {
            File::delete($this->testTargetPath);
        }

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['currentCount']);
        $this->assertEquals(2, $result['newCount']);
        $this->assertEquals(2, $result['mergedCount']);
        $this->assertNull($result['backupPath']);
        $this->assertTrue(File::exists($this->testTargetPath));
    }

    /**
     * Test mergeTranslations creates backup of existing target file
     */
    public function test_merge_translations_creates_backup()
    {
        // Arrange
        $sourceData = ['key1' => 'value1'];
        $targetData = ['key2' => 'value2'];

        File::put($this->testSourcePath, json_encode($sourceData));
        File::put($this->testTargetPath, json_encode($targetData));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['backupPath']);
        $this->assertTrue(File::exists($result['backupPath']));

        // Verify backup content matches original
        $backupContent = json_decode(File::get($result['backupPath']), true);
        $this->assertEquals($targetData, $backupContent);
    }

    /**
     * Test mergeTranslations sorts keys alphabetically
     */
    public function test_merge_translations_sorts_keys()
    {
        // Arrange
        $sourceData = ['zebra' => 'z', 'apple' => 'a'];
        $targetData = ['monkey' => 'm', 'banana' => 'b'];

        File::put($this->testSourcePath, json_encode($sourceData));
        File::put($this->testTargetPath, json_encode($targetData));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);

        $mergedContent = json_decode(File::get($this->testTargetPath), true);
        $keys = array_keys($mergedContent);
        $this->assertEquals(['apple', 'banana', 'monkey', 'zebra'], $keys);
    }

    /**
     * Test getLanguageName returns correct names
     */
    public function test_get_language_name_returns_correct_names()
    {
        // Act & Assert
        $this->assertEquals('Arabic', $this->translationMergeService->getLanguageName('ar'));
        $this->assertEquals('French', $this->translationMergeService->getLanguageName('fr'));
        $this->assertEquals('English', $this->translationMergeService->getLanguageName('en'));
        $this->assertEquals('Spanish', $this->translationMergeService->getLanguageName('es'));
        $this->assertEquals('Turkish', $this->translationMergeService->getLanguageName('tr'));
        $this->assertEquals('German', $this->translationMergeService->getLanguageName('de'));
        $this->assertEquals('Russian', $this->translationMergeService->getLanguageName('ru'));
    }

    /**
     * Test getLanguageName returns capitalized code for unknown languages
     */
    public function test_get_language_name_returns_capitalized_unknown()
    {
        // Act & Assert
        $this->assertEquals('Jp', $this->translationMergeService->getLanguageName('jp'));
        $this->assertEquals('Zh', $this->translationMergeService->getLanguageName('zh'));
    }

    /**
     * Test getDefaultSourcePath returns correct path
     */
    public function test_get_default_source_path_returns_correct_path()
    {
        // Act
        $result = $this->translationMergeService->getDefaultSourcePath('ar');

        // Assert
        $expected = base_path("new trans/ar.json");
        $this->assertEquals($expected, $result);
    }

    /**
     * Test mergeTranslations handles invalid JSON in source
     */
    public function test_merge_translations_fails_with_invalid_source_json()
    {
        // Arrange
        File::put($this->testSourcePath, 'invalid json {{{');
        File::put($this->testTargetPath, json_encode(['key' => 'value']));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Could not parse source translations file', $result['message']);
    }

    /**
     * Test mergeTranslations handles invalid JSON in target
     */
    public function test_merge_translations_fails_with_invalid_target_json()
    {
        // Arrange
        File::put($this->testSourcePath, json_encode(['key' => 'value']));
        File::put($this->testTargetPath, 'invalid json {{{');

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Could not parse current test.json file', $result['message']);
    }

    /**
     * Test mergeTranslations returns sample of merged translations
     */
    public function test_merge_translations_returns_sample()
    {
        // Arrange
        $sourceData = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
            'key5' => 'value5',
            'key6' => 'value6',
        ];

        File::put($this->testSourcePath, json_encode($sourceData));

        // Act
        $result = $this->translationMergeService->mergeTranslations($this->testSourcePath, 'test');

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('sample', $result);
        $this->assertCount(5, $result['sample']); // Sample is limited to 5

    }

    /**
     * Test getDefaultSourcePath method
     * TODO: Implement actual test logic
     */
    public function test_get_default_source_path_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getDefaultSourcePath();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getDefaultSourcePath not yet implemented');
    }
}
