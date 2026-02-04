<?php

namespace Tests\Unit\Services\Hashtag;

use App\Models\Hashtag;
use App\Services\Hashtag\HashtagService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HashtagServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected HashtagService $hashtagService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hashtagService = new HashtagService();
    }

    /**
     * Test getHashtags returns all hashtags
     */
    public function test_get_hashtags_returns_all_hashtags()
    {
        // Arrange
        Hashtag::factory()->count(3)->create();

        // Act
        $result = $this->hashtagService->getHashtags();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getHashtags with search filter by name
     */
    public function test_get_hashtags_filters_by_name()
    {
        // Arrange
        Hashtag::factory()->create(['name' => 'Laravel Tips']);
        Hashtag::factory()->create(['name' => 'PHP Best Practices']);
        Hashtag::factory()->create(['name' => 'Vue.js Guide']);

        // Act
        $result = $this->hashtagService->getHashtags(['search' => 'Laravel']);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Laravel Tips', $result->first()->name);
    }

    /**
     * Test getHashtags with search filter by slug
     */
    public function test_get_hashtags_filters_by_slug()
    {
        // Arrange
        Hashtag::factory()->create(['name' => 'Test', 'slug' => 'laravel-tips']);
        Hashtag::factory()->create(['name' => 'Another', 'slug' => 'php-guide']);

        // Act
        $result = $this->hashtagService->getHashtags(['search' => 'laravel-tips']);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('laravel-tips', $result->first()->slug);
    }

    /**
     * Test getHashtags with pagination
     */
    public function test_get_hashtags_returns_paginated_results()
    {
        // Arrange
        Hashtag::factory()->count(20)->create();

        // Act
        $result = $this->hashtagService->getHashtags(['PAGE_SIZE' => 10]);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual(20, $result->total());
    }

    /**
     * Test getHashtags with custom ordering
     */
    public function test_get_hashtags_with_custom_ordering()
    {
        // Arrange
        // Use a unique prefix to avoid interference with existing rows
        Hashtag::factory()->create(['name' => 'X_Zebra']);
        Hashtag::factory()->create(['name' => 'X_Apple']);

        // Act
        $result = $this->hashtagService->getHashtags(['search' => 'X_', 'order_by' => 'name', 'order_direction' => 'asc']);

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->count());
        $this->assertEquals('X_Apple', $result->first()->name);
    }

    /**
     * Test getHashtags with relationships
     */
    public function test_get_hashtags_loads_relationships()
    {
        // Arrange
        Hashtag::factory()->create();

        // Act
        $result = $this->hashtagService->getHashtags(['with' => ['news']]);

        // Assert
        $this->assertTrue($result->first()->relationLoaded('news'));
    }

    /**
     * Test getHashtagById returns correct hashtag
     */
    public function test_get_hashtag_by_id_returns_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create();

        // Act
        $result = $this->hashtagService->getHashtagById($hashtag->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($hashtag->id, $result->id);
        $this->assertEquals($hashtag->name, $result->name);
    }

    /**
     * Test getHashtagById returns null when not found
     */
    public function test_get_hashtag_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->hashtagService->getHashtagById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getHashtagById with relationships
     */
    public function test_get_hashtag_by_id_loads_relationships()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create();

        // Act
        $result = $this->hashtagService->getHashtagById($hashtag->id, ['news']);

        // Assert
        $this->assertTrue($result->relationLoaded('news'));
    }

    /**
     * Test createHashtag creates new hashtag
     */
    public function test_create_hashtag_creates_new_hashtag()
    {
        // Arrange
        $data = [
            'name' => 'New Hashtag',
            'slug' => 'new-hashtag',
        ];

        // Act
        $result = $this->hashtagService->createHashtag($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New Hashtag', $result->name);
        $this->assertEquals('new-hashtag', $result->slug);
        $this->assertDatabaseHas('hashtags', ['name' => 'New Hashtag']);
    }

    /**
     * Test createHashtag auto-generates slug from name
     */
    public function test_create_hashtag_auto_generates_slug()
    {
        // Arrange
        $data = ['name' => 'Laravel Best Practices'];

        // Act
        $result = $this->hashtagService->createHashtag($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('laravel-best-practices', $result->slug);
    }

    /**
     * Test updateHashtag updates hashtag
     */
    public function test_update_hashtag_updates_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->hashtagService->updateHashtag($hashtag->id, $data);

        // Assert
        $this->assertTrue($result);
        $hashtag->refresh();
        $this->assertEquals('New Name', $hashtag->name);
    }

    /**
     * Test updateHashtag auto-generates slug when name changes
     */
    public function test_update_hashtag_auto_generates_slug_from_name()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);
        $data = ['name' => 'Updated Name'];

        // Act
        $result = $this->hashtagService->updateHashtag($hashtag->id, $data);

        // Assert
        $this->assertTrue($result);
        $hashtag->refresh();
        $this->assertEquals('updated-name', $hashtag->slug);
    }

    /**
     * Test updateHashtag returns false when not found
     */
    public function test_update_hashtag_returns_false_when_not_found()
    {
        // Act
        $result = $this->hashtagService->updateHashtag(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteHashtag deletes hashtag
     */
    public function test_delete_hashtag_deletes_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create();

        // Act
        $result = $this->hashtagService->deleteHashtag($hashtag->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('hashtags', ['id' => $hashtag->id]);
    }

    /**
     * Test deleteHashtag returns false when not found
     */
    public function test_delete_hashtag_returns_false_when_not_found()
    {
        // Act
        $result = $this->hashtagService->deleteHashtag(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hashtagExists checks if hashtag name exists
     */
    public function test_hashtag_exists_returns_true_when_name_exists()
    {
        // Arrange
        Hashtag::factory()->create(['name' => 'Existing Tag']);

        // Act
        $result = $this->hashtagService->hashtagExists('Existing Tag');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hashtagExists returns false when name does not exist
     */
    public function test_hashtag_exists_returns_false_when_name_does_not_exist()
    {
        // Act
        $result = $this->hashtagService->hashtagExists('Non Existent Tag');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hashtagExists excludes specific ID
     */
    public function test_hashtag_exists_excludes_specific_id()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create(['name' => 'Test Tag']);

        // Act
        $result = $this->hashtagService->hashtagExists('Test Tag', $hashtag->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hashtagExists includes other IDs
     */
    public function test_hashtag_exists_includes_other_ids()
    {
        // Arrange
        $hashtag1 = Hashtag::factory()->create(['name' => 'Test Tag']);
        $hashtag2 = Hashtag::factory()->create(['name' => 'Another Tag']);

        // Act
        $result = $this->hashtagService->hashtagExists('Test Tag', $hashtag2->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getHashtagBySlug returns hashtag by slug
     */
    public function test_get_hashtag_by_slug_returns_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create(['slug' => 'test-slug']);

        // Act
        $result = $this->hashtagService->getHashtagBySlug('test-slug');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('test-slug', $result->slug);
    }

    /**
     * Test getHashtagBySlug returns null when not found
     */
    public function test_get_hashtag_by_slug_returns_null_when_not_found()
    {
        // Act
        $result = $this->hashtagService->getHashtagBySlug('non-existent-slug');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getAll returns all hashtags
     */
    public function test_get_all_returns_all_hashtags()
    {
        // Arrange
        Hashtag::factory()->count(5)->create();

        // Act
        $result = $this->hashtagService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual(5, $result->count());
    }

    /**
     * Test findByIdOrFail returns hashtag
     */
    public function test_find_by_id_or_fail_returns_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create();

        // Act
        $result = $this->hashtagService->findByIdOrFail($hashtag->id);

        // Assert
        $this->assertEquals($hashtag->id, $result->id);
    }

    /**
     * Test findByIdOrFail throws exception when not found
     */
    public function test_find_by_id_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->hashtagService->findByIdOrFail(99999);
    }

    /**
     * Test create method creates hashtag
     */
    public function test_create_creates_hashtag()
    {
        // Arrange
        $data = ['name' => 'New Tag', 'slug' => 'new-tag'];

        // Act
        $result = $this->hashtagService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New Tag', $result->name);
        $this->assertDatabaseHas('hashtags', ['name' => 'New Tag']);
    }

    /**
     * Test update method updates hashtag
     */
    public function test_update_updates_hashtag()
    {
        // Arrange
        $hashtag = Hashtag::factory()->create(['name' => 'Original']);
        $data = ['name' => 'Modified'];

        // Act
        $result = $this->hashtagService->update($hashtag->id, $data);

        // Assert
        $this->assertTrue($result);
        $hashtag->refresh();
        $this->assertEquals('Modified', $hashtag->name);
    }
}
