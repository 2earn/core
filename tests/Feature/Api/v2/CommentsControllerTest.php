<?php

namespace Tests\Feature\Api\v2;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for CommentsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\CommentsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('comments')]
class CommentsControllerTest extends TestCase
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
    public function it_can_get_validated_comments()
    {
        Comment::factory()->count(3)->create(['validated' => true]);

        $response = $this->getJson('/api/v2/comments/validated');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_unvalidated_comments()
    {
        Comment::factory()->count(2)->create(['validated' => false]);

        $response = $this->getJson('/api/v2/comments/unvalidated');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_all_comments()
    {
        Comment::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/comments/all');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_comments_count()
    {
        Comment::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/comments/count');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_check_if_user_has_commented()
    {
        $data = [
            'user_id' => $this->user->id,
            'commentable_type' => 'App\\Models\\Post',
            'commentable_id' => 1
        ];

        $response = $this->getJson('/api/v2/comments/has-commented?' . http_build_query($data));

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_create_comment()
    {
        $data = [
            'content' => 'Test Comment',
            'commentable_type' => 'App\\Models\\Post',
            'commentable_id' => 1,
            'user_id' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/comments/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', ['content' => 'Test Comment']);
    }

    #[Test]
    public function it_can_validate_comment()
    {
        $comment = Comment::factory()->create(['validated' => false]);

        $response = $this->postJson("/api/v2/comments/{$comment->id}/validate");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_delete_comment()
    {
        $comment = Comment::factory()->create();

        $response = $this->deleteJson("/api/v2/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}

