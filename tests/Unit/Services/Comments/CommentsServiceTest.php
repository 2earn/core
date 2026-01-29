<?php

namespace Tests\Unit\Services\Comments;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use App\Services\Comments\CommentsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CommentsService $commentsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentsService = new CommentsService();
    }

    /**
     * Test getValidatedComments returns only validated comments
     */
    public function test_get_validated_comments_works()
    {
        // Arrange
        $event = Event::factory()->create();
        Comment::factory()->validated()->count(3)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);
        Comment::factory()->unvalidated()->count(2)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);

        // Act
        $result = $this->commentsService->getValidatedComments($event);

        // Assert
        $this->assertCount(3, $result);
        foreach ($result as $comment) {
            $this->assertTrue($comment->validated);
        }
    }

    /**
     * Test getValidatedComments orders by created_at desc by default
     */
    public function test_get_validated_comments_orders_correctly()
    {
        // Arrange
        $event = Event::factory()->create();
        $comment1 = Comment::factory()->validated()->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
            'created_at' => now()->subDays(2)
        ]);
        $comment2 = Comment::factory()->validated()->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
            'created_at' => now()
        ]);

        // Act
        $result = $this->commentsService->getValidatedComments($event);

        // Assert
        $this->assertEquals($comment2->id, $result->first()->id);
    }

    /**
     * Test getUnvalidatedComments returns only unvalidated comments
     */
    public function test_get_unvalidated_comments_works()
    {
        // Arrange
        $event = Event::factory()->create();
        Comment::factory()->validated()->count(2)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);
        Comment::factory()->unvalidated()->count(3)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);

        // Act
        $result = $this->commentsService->getUnvalidatedComments($event);

        // Assert
        $this->assertCount(3, $result);
        foreach ($result as $comment) {
            $this->assertFalse($comment->validated);
        }
    }

    /**
     * Test getAllComments returns both validated and unvalidated
     */
    public function test_get_all_comments_works()
    {
        // Arrange
        $event = Event::factory()->create();
        Comment::factory()->validated()->count(2)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);
        Comment::factory()->unvalidated()->count(3)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);

        // Act
        $result = $this->commentsService->getAllComments($event);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('validated', $result);
        $this->assertArrayHasKey('unvalidated', $result);
        $this->assertCount(2, $result['validated']);
        $this->assertCount(3, $result['unvalidated']);
    }

    /**
     * Test addComment adds a comment
     */
    public function test_add_comment_works()
    {
        // Arrange
        $event = Event::factory()->create();
        $user = User::factory()->create();
        $content = 'This is a test comment';

        // Act
        $result = $this->commentsService->addComment($event, $content, $user->id, false);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($content, $result->content);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertFalse($result->validated);
        $this->assertDatabaseHas('comments', [
            'content' => $content,
            'user_id' => $user->id,
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);
    }

    /**
     * Test addComment with pre-validated flag
     */
    public function test_add_comment_with_validated_flag()
    {
        // Arrange
        $event = Event::factory()->create();
        $user = User::factory()->create();

        // Act
        $result = $this->commentsService->addComment($event, 'Test', $user->id, true);

        // Assert
        $this->assertTrue($result->validated);
    }

    /**
     * Test validateComment validates a comment
     */
    public function test_validate_comment_works()
    {
        // Arrange
        $comment = Comment::factory()->unvalidated()->create();
        $validator = User::factory()->create();

        // Act
        $result = $this->commentsService->validateComment($comment->id, $validator->id);

        // Assert
        $this->assertTrue($result);
        $comment->refresh();
        $this->assertTrue($comment->validated);
        $this->assertEquals($validator->id, $comment->validatedBy_id);
        $this->assertNotNull($comment->validatedAt);
    }

    /**
     * Test deleteComment deletes a comment
     */
    public function test_delete_comment_works()
    {
        // Arrange
        $comment = Comment::factory()->create();

        // Act
        $result = $this->commentsService->deleteComment($comment->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test getCommentCount returns correct count
     */
    public function test_get_comment_count_works()
    {
        // Arrange
        $event = Event::factory()->create();
        Comment::factory()->validated()->count(3)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);
        Comment::factory()->unvalidated()->count(2)->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
        ]);

        // Act
        $totalCount = $this->commentsService->getCommentCount($event);
        $validatedCount = $this->commentsService->getCommentCount($event, true);
        $unvalidatedCount = $this->commentsService->getCommentCount($event, false);

        // Assert
        $this->assertEquals(5, $totalCount);
        $this->assertEquals(3, $validatedCount);
        $this->assertEquals(2, $unvalidatedCount);
    }

    /**
     * Test hasUserCommented returns true when user has commented
     */
    public function test_has_user_commented_works()
    {
        // Arrange
        $event = Event::factory()->create();
        $user = User::factory()->create();
        Comment::factory()->create([
            'commentable_id' => $event->id,
            'commentable_type' => Event::class,
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->commentsService->hasUserCommented($event, $user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasUserCommented returns false when user has not commented
     */
    public function test_has_user_commented_returns_false_when_user_has_not_commented()
    {
        // Arrange
        $event = Event::factory()->create();
        $user = User::factory()->create();

        // Act
        $result = $this->commentsService->hasUserCommented($event, $user->id);

        // Assert
        $this->assertFalse($result);
    }
}
