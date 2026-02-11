<?php

namespace Tests\Feature\Api\v2;

use App\Models\Hashtag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for HashtagController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\HashtagController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('hashtags')]
class HashtagControllerTest extends TestCase
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
    public function it_can_get_all_hashtags()
    {
        Hashtag::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/hashtags');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_filtered_hashtags()
    {
        Hashtag::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/hashtags/filtered?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_hashtags()
    {
        Hashtag::factory()->create(['name' => 'TestTag']);

        $response = $this->getJson('/api/v2/hashtags/filtered?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_hashtag_by_id()
    {
        $hashtag = Hashtag::factory()->create();

        $response = $this->getJson("/api/v2/hashtags/{$hashtag->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_hashtag()
    {
        $response = $this->getJson('/api/v2/hashtags/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_get_hashtag_by_slug()
    {
        $hashtag = Hashtag::factory()->create(['slug' => 'test-hashtag']);

        $response = $this->getJson('/api/v2/hashtags/slug/test-hashtag');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_hashtag()
    {
        $data = [
            'name' => 'NewTag',
            'slug' => 'new-tag'
        ];

        $response = $this->postJson('/api/v2/hashtags', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_hashtag_creation()
    {
        $response = $this->postJson('/api/v2/hashtags', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function it_can_update_hashtag()
    {
        $hashtag = Hashtag::factory()->create();

        $data = [
            'name' => 'UpdatedTag'
        ];

        $response = $this->putJson("/api/v2/hashtags/{$hashtag->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_delete_hashtag()
    {
        $hashtag = Hashtag::factory()->create();

        $response = $this->deleteJson("/api/v2/hashtags/{$hashtag->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_with_order_by()
    {
        Hashtag::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/hashtags/filtered?order_by=name&order_direction=asc');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/hashtags/filtered?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

