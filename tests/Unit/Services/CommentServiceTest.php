<?php

namespace Tests\Unit\Services;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use App\Services\CommentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

/**
 * @group unit
 * @group service
 * @group fast
 */
class CommentServiceTest extends TestCase
{

    protected CommentService $commentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentService = new CommentService();
    }

    /**
     * Test finding comment by ID or fail
     */
    public function test_find_by_id_or_fail_returns_comment()
    {
        // Arrange
        $comment = Comment::factory()->create();

        // Act
        $result = $this->commentService->findByIdOrFail($comment->id);

        // Assert
        $this->assertInstanceOf(Comment::class, $result);
        $this->assertEquals($comment->id, $result->id);
    }

    /**
     * Test finding comment by ID or fail throws exception
     */
    public function test_find_by_id_or_fail_throws_exception_when_not_exists()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->commentService->findByIdOrFail(9999);
    }

    /**
     * Test deleting a comment successfully
     */
    public function test_delete_successfully_deletes_comment()
    {
        // Arrange
        $comment = Comment::factory()->create();

        // Act
        $result = $this->commentService->delete($comment->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test deleting non-existent comment
     */
    public function test_delete_returns_false_when_comment_not_found()
    {
        // Act
        $result = $this->commentService->delete(9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test validating a comment
     */
    public function test_validate_comment_successfully_validates()
    {
        // Arrange
        $validator = User::factory()->create();
        $comment = Comment::factory()->create(['validated' => false]);

        // Act
        $result = $this->commentService->validateComment($comment->id, $validator->id);

        // Assert
        $this->assertTrue($result);
        $comment->refresh();
        $this->assertTrue($comment->validated);
        $this->assertEquals($validator->id, $comment->validatedBy_id);
        $this->assertNotNull($comment->validatedAt);
    }

    /**
     * Test validating non-existent comment
     */
    public function test_validate_comment_returns_false_when_not_found()
    {
        // Arrange
        $validator = User::factory()->create();

        // Act
        $result = $this->commentService->validateComment(9999, $validator->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteComment alias method
     */
    public function test_delete_comment_alias_works()
    {
        // Arrange
        $comment = Comment::factory()->create();

        // Act
        $result = $this->commentService->deleteComment($comment->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    /**
     * Test creating a comment for news
     */
    public function test_create_comment_successfully_creates()
    {
        // Arrange
        $news = News::factory()->create();
        $user = User::factory()->create();
        $content = 'This is a test comment';

        // Act
        $result = $this->commentService->createComment($news->id, $user->id, $content);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Comment created successfully', $result['message']);
        $this->assertArrayHasKey('comment', $result);
        $this->assertInstanceOf(Comment::class, $result['comment']);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'content' => $content
        ]);
    }

    /**
     * Test creating comment for non-existent news
     */
    public function test_create_comment_returns_error_when_news_not_found()
    {
        // Arrange
        $user = User::factory()->create();
        $content = 'Test comment';

        // Act
        $result = $this->commentService->createComment(9999, $user->id, $content);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('Error creating comment', $result['message']);
    }
}
