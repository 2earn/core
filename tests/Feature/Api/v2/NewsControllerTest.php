<?php

namespace Tests\Feature\Api\v2;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for NewsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\NewsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('news')]
class NewsControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_get_paginated_news()
    {
        News::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/news?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_news()
    {
        News::factory()->create(['title' => 'Test News']);

        $response = $this->getJson('/api/v2/news?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_news()
    {
        News::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/news/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_all_news_with_relationships()
    {
        News::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/news/all?with[]=author');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_enabled_news()
    {
        News::factory()->count(2)->create(['enabled' => true]);
        News::factory()->count(2)->create(['enabled' => false]);

        $response = $this->getJson('/api/v2/news/enabled');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_news_by_id()
    {
        $news = News::factory()->create();

        $response = $this->getJson("/api/v2/news/{$news->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_news()
    {
        $response = $this->getJson('/api/v2/news/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_create_news()
    {
        $data = [
            'title' => 'Test News',
            'content' => 'Test Content',
            'enabled' => true
        ];

        $response = $this->postJson('/api/v2/news', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_news_creation()
    {
        $response = $this->postJson('/api/v2/news', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'content']);
    }

    #[Test]
    public function it_can_update_news()
    {
        $news = News::factory()->create();

        $data = [
            'title' => 'Updated News',
            'content' => 'Updated Content'
        ];

        $response = $this->putJson("/api/v2/news/{$news->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_delete_news()
    {
        $news = News::factory()->create();

        $response = $this->deleteJson("/api/v2/news/{$news->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/news?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

