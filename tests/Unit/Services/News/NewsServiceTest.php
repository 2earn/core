<?php

namespace Tests\Unit\Services\News;

use App\Models\News;
use App\Models\User;
use App\Services\News\NewsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NewsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected NewsService $newsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsService = new NewsService();
    }

    /**
     * Test getById returns correct news
     */
    public function test_get_by_id_returns_news()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->getById($news->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($news->id, $result->id);
        $this->assertEquals($news->title, $result->title);
    }

    /**
     * Test getById returns null when not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->newsService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getById loads relationships
     */
    public function test_get_by_id_loads_relationships()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->getById($news->id, ['mainImage', 'hashtags']);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('mainImage'));
        $this->assertTrue($result->relationLoaded('hashtags'));
    }

    /**
     * Test getByIdOrFail returns correct news
     */
    public function test_get_by_id_or_fail_returns_news()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->getByIdOrFail($news->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($news->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception when not found
     */
    public function test_get_by_id_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->newsService->getByIdOrFail(99999);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        $initialCount = News::count();
        News::factory()->count(15)->create();

        // Act
        $result = $this->newsService->getPaginated(null, 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual($initialCount + 15, $result->total());
    }

    /**
     * Test getPaginated filters by search term
     */
    public function test_get_paginated_filters_by_search()
    {
        // Arrange
        $uniqueTitle = 'Unique Search Term ' . uniqid();
        News::factory()->create(['title' => $uniqueTitle]);
        News::factory()->count(3)->create(['title' => 'Other News']);

        // Act
        $result = $this->newsService->getPaginated('Unique Search Term', 10);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->total());
        $this->assertStringContainsString('Unique', $result->items()[0]->title);
    }

    /**
     * Test getAll returns all news
     */
    public function test_get_all_returns_all_news()
    {
        // Arrange
        $initialCount = News::count();
        News::factory()->count(5)->create();

        // Act
        $result = $this->newsService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 5, $result->count());
    }

    /**
     * Test getAll loads relationships
     */
    public function test_get_all_loads_relationships()
    {
        // Arrange
        $initialCount = News::count();
        News::factory()->count(3)->create();

        // Act
        $result = $this->newsService->getAll(['mainImage', 'hashtags']);

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
        // Check that first item has relationships loaded
        if ($result->count() > 0) {
            $this->assertTrue($result->first()->relationLoaded('mainImage'));
            $this->assertTrue($result->first()->relationLoaded('hashtags'));
        }
    }

    /**
     * Test getEnabledNews returns only enabled news
     */
    public function test_get_enabled_news_returns_only_enabled()
    {
        // Arrange
        $initialCount = News::where('enabled', 1)->count();
        News::factory()->enabled()->count(3)->create();
        News::factory()->disabled()->count(2)->create();

        // Act
        $result = $this->newsService->getEnabledNews();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
        $this->assertTrue($result->every(fn($news) => $news->enabled == 1));
    }

    /**
     * Test create creates new news
     */
    public function test_create_creates_new_news()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'title' => 'Test News Title',
            'content' => 'Test news content',
            'enabled' => true,
            'created_by' => $user->id,
        ];

        // Act
        $result = $this->newsService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($data['title'], $result->title);
        $this->assertEquals($data['content'], $result->content);
        $this->assertEquals($data['enabled'], $result->enabled);
        $this->assertDatabaseHas('news', ['title' => 'Test News Title']);
    }

    /**
     * Test update updates existing news
     */
    public function test_update_updates_news()
    {
        // Arrange
        $news = News::factory()->create(['title' => 'Original Title']);
        $updateData = ['title' => 'Updated Title'];

        // Act
        $result = $this->newsService->update($news->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $news->refresh();
        $this->assertEquals('Updated Title', $news->title);
    }

    /**
     * Test update throws exception when not found
     */
    public function test_update_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->newsService->update(99999, ['title' => 'Test']);
    }

    /**
     * Test delete deletes news
     */
    public function test_delete_deletes_news()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->delete($news->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    /**
     * Test delete throws exception when not found
     */
    public function test_delete_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->newsService->delete(99999);
    }

    /**
     * Test duplicate creates copy of news
     */
    public function test_duplicate_creates_copy()
    {
        // Arrange
        $original = News::factory()->create([
            'title' => 'Original News',
            'content' => 'Original Content',
            'enabled' => true
        ]);

        // Act
        $duplicate = $this->newsService->duplicate($original->id);

        // Assert
        $this->assertNotNull($duplicate);
        $this->assertNotEquals($original->id, $duplicate->id);
        $this->assertStringContainsString('Original News', $duplicate->title);
        $this->assertStringContainsString('(Copy)', $duplicate->title);
        $this->assertStringContainsString('(Copy)', $duplicate->content);
        $this->assertFalse($duplicate->enabled);
    }

    /**
     * Test hasUserLiked returns false when not liked
     */
    public function test_has_user_liked_returns_false_when_not_liked()
    {
        // Arrange
        $news = News::factory()->create();
        $user = User::factory()->create();

        // Act
        $result = $this->newsService->hasUserLiked($news->id, $user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getWithRelations loads specified relations
     */
    public function test_get_with_relations_loads_specified_relations()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->getWithRelations($news->id, ['likes', 'comments']);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('likes'));
        $this->assertTrue($result->relationLoaded('comments'));
    }

    /**
     * Test getWithRelations returns null when not found
     */
    public function test_get_with_relations_returns_null_when_not_found()
    {
        // Act
        $result = $this->newsService->getWithRelations(99999, ['likes']);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getNewsWithRelations loads standard relations
     */
    public function test_get_news_with_relations_loads_standard_relations()
    {
        // Arrange
        $news = News::factory()->create();

        // Act
        $result = $this->newsService->getNewsWithRelations($news->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result->relationLoaded('mainImage'));
        $this->assertTrue($result->relationLoaded('likes'));
        $this->assertTrue($result->relationLoaded('comments'));
    }

    /**
     * Test addLike adds like to news
     */
    public function test_add_like_adds_like()
    {
        // Arrange
        $news = News::factory()->create();
        $user = User::factory()->create();

        // Act
        $result = $this->newsService->addLike($news->id, $user->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('likes', [
            'likable_id' => $news->id,
            'likable_type' => News::class,
            'user_id' => $user->id
        ]);
    }

    /**
     * Test addLike handles duplicate likes
     */
    public function test_add_like_handles_duplicate()
    {
        // Arrange
        $news = News::factory()->create();
        $user = User::factory()->create();
        $news->likes()->create(['user_id' => $user->id]);

        // Act
        $result = $this->newsService->addLike($news->id, $user->id);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(1, $news->likes()->count());
    }

    /**
     * Test removeLike removes like from news
     */
    public function test_remove_like_removes_like()
    {
        // Arrange
        $news = News::factory()->create();
        $user = User::factory()->create();
        $news->likes()->create(['user_id' => $user->id]);

        // Act
        $result = $this->newsService->removeLike($news->id, $user->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('likes', [
            'likable_id' => $news->id,
            'likable_type' => News::class,
            'user_id' => $user->id
        ]);
    }
}
