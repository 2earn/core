<?php

namespace Tests\Unit\Services\UserGuide;

use App\Models\User;
use App\Models\UserGuide;
use App\Services\UserGuide\UserGuideService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserGuideServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserGuideService $userGuideService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userGuideService = new UserGuideService();
    }

    /**
     * Test getting user guide by ID with relationships
     */
    public function test_get_by_id_returns_user_guide_with_user()
    {
        // Arrange
        $user = User::factory()->create();
        $guide = UserGuide::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getById($guide->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(UserGuide::class, $result);
        $this->assertEquals($guide->id, $result->id);
        $this->assertTrue($result->relationLoaded('user'));
    }

    /**
     * Test getting user guide by ID when not exists
     */
    public function test_get_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->userGuideService->getById(9999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getting user guide by ID or fail
     */
    public function test_get_by_id_or_fail_returns_user_guide()
    {
        // Arrange
        $user = User::factory()->create();
        $guide = UserGuide::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getByIdOrFail($guide->id);

        // Assert
        $this->assertInstanceOf(UserGuide::class, $result);
        $this->assertEquals($guide->id, $result->id);
    }

    /**
     * Test getting user guide by ID or fail throws exception
     */
    public function test_get_by_id_or_fail_throws_exception_when_not_exists()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->userGuideService->getByIdOrFail(9999);
    }

    /**
     * Test getting paginated user guides without search
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->count(15)->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getPaginated(null, 10);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }

    /**
     * Test getting paginated user guides with search
     */
    public function test_get_paginated_filters_by_search_term()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'Laravel Testing Guide'
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'Laravel Deployment Guide'
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'PHP Basics'
        ]);

        // Act
        $result = $this->userGuideService->getPaginated('Laravel', 10);

        // Assert
        $this->assertEquals(2, $result->total());
    }

    /**
     * Test getting all user guides
     */
    public function test_get_all_returns_all_guides()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->count(5)->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getAll();

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test creating a new user guide
     */
    public function test_create_successfully_creates_user_guide()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'title' => 'New Guide',
            'description' => 'Guide Description',
            'file_path' => 'path/to/file.pdf',
            'user_id' => $user->id,
            'routes' => ['home', 'dashboard']
        ];

        // Act
        $result = $this->userGuideService->create($data);

        // Assert
        $this->assertInstanceOf(UserGuide::class, $result);
        $this->assertEquals('New Guide', $result->title);
        $this->assertDatabaseHas('user_guides', [
            'title' => 'New Guide',
            'user_id' => $user->id
        ]);
    }

    /**
     * Test updating a user guide
     */
    public function test_update_successfully_updates_user_guide()
    {
        // Arrange
        $user = User::factory()->create();
        $guide = UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title'
        ]);
        $updateData = ['title' => 'Updated Title'];

        // Act
        $result = $this->userGuideService->update($guide->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('user_guides', [
            'id' => $guide->id,
            'title' => 'Updated Title'
        ]);
    }

    /**
     * Test deleting a user guide
     */
    public function test_delete_successfully_deletes_user_guide()
    {
        // Arrange
        $user = User::factory()->create();
        $guide = UserGuide::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->delete($guide->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('user_guides', [
            'id' => $guide->id
        ]);
    }

    /**
     * Test searching user guides
     */
    public function test_search_returns_matching_guides()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'Testing in Laravel'
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'description' => 'A guide about Laravel testing'
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'title' => 'PHP Basics'
        ]);

        // Act
        $result = $this->userGuideService->search('Laravel');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test getting user guides by route name
     */
    public function test_get_by_route_name_returns_matching_guides()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'routes' => ['home', 'dashboard']
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'routes' => ['profile', 'settings']
        ]);
        UserGuide::factory()->create([
            'user_id' => $user->id,
            'routes' => ['home', 'profile']
        ]);

        // Act
        $result = $this->userGuideService->getByRouteName('home');

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test getting user guides by user ID
     */
    public function test_get_by_user_id_returns_user_guides()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        UserGuide::factory()->count(3)->create(['user_id' => $user1->id]);
        UserGuide::factory()->count(2)->create(['user_id' => $user2->id]);

        // Act
        $result = $this->userGuideService->getByUserId($user1->id);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test checking if user guide exists
     */
    public function test_exists_returns_true_when_guide_exists()
    {
        // Arrange
        $user = User::factory()->create();
        $guide = UserGuide::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->exists($guide->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test checking if user guide exists when it doesn't
     */
    public function test_exists_returns_false_when_guide_not_exists()
    {
        // Act
        $result = $this->userGuideService->exists(9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test counting user guides
     */
    public function test_count_returns_correct_count()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->count(7)->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->count();

        // Assert
        $this->assertEquals(7, $result);
    }

    /**
     * Test getting recent user guides
     */
    public function test_get_recent_returns_limited_guides()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->count(10)->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getRecent(5);

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getting recent user guides with default limit
     */
    public function test_get_recent_uses_default_limit()
    {
        // Arrange
        $user = User::factory()->create();
        UserGuide::factory()->count(10)->create(['user_id' => $user->id]);

        // Act
        $result = $this->userGuideService->getRecent();

        // Assert
        $this->assertCount(5, $result);
    }
}
