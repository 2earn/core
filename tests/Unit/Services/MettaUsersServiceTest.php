<?php

namespace Tests\Unit\Services;

use App\Interfaces\IUserRepository;
use App\Models\MettaUser;
use App\Models\User;
use App\Services\MettaUsersService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MettaUsersServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected MettaUsersService $mettaUsersService;
    protected IUserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(IUserRepository::class);
        $this->mettaUsersService = new MettaUsersService($this->userRepository);
    }

    /**
     * Test getMettaUserInfo returns collection
     */
    public function test_get_metta_user_info_returns_collection()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->mettaUsersService->getMettaUserInfo($user->idUser);

        // Assert
        $this->assertIsObject($result);
        $this->assertNotEmpty($result);
    }

    /**
     * Test getMettaUserInfo returns empty collection for non-existent user
     */
    public function test_get_metta_user_info_returns_empty_for_nonexistent()
    {
        // Act
        $result = $this->mettaUsersService->getMettaUserInfo(99999);

        // Assert
        $this->assertIsObject($result);
    }

    /**
     * Test getMettaUser returns metta user
     */
    public function test_get_metta_user_returns_metta_user()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->mettaUsersService->getMettaUser($user->idUser);

        // Assert
        $this->assertInstanceOf(MettaUser::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
    }

    /**
     * Test getMettaUser returns null for non-existent user
     */
    public function test_get_metta_user_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->mettaUsersService->getMettaUser(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getMettaUserFullName returns full name
     */
    public function test_get_metta_user_full_name_returns_full_name()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'John',
            'enLastName' => 'Doe'
        ]);

        // Act
        $result = $this->mettaUsersService->getMettaUserFullName($user->idUser);

        // Assert
        $this->assertEquals('John Doe', $result);
    }

    /**
     * Test getMettaUserFullName returns empty string for non-existent
     */
    public function test_get_metta_user_full_name_returns_empty_for_nonexistent()
    {
        // Act
        $result = $this->mettaUsersService->getMettaUserFullName(99999);

        // Assert
        $this->assertEquals('', $result);
    }

    /**
     * Test getMettaUserFullName with only first name
     */
    public function test_get_metta_user_full_name_with_only_first_name()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'John',
            'enLastName' => null
        ]);

        // Act
        $result = $this->mettaUsersService->getMettaUserFullName($user->idUser);

        // Assert
        $this->assertEquals('John', $result);
    }

    /**
     * Test mettaUserExists returns true when exists
     */
    public function test_metta_user_exists_returns_true()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->mettaUsersService->mettaUserExists($user->idUser);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test mettaUserExists returns false when not exists
     */
    public function test_metta_user_exists_returns_false()
    {
        // Act
        $result = $this->mettaUsersService->mettaUserExists(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test createMettaUser creates user
     */
    public function test_create_metta_user_creates_user()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'idUser' => $user->idUser,
            'enFirstName' => 'Test',
            'enLastName' => 'User'
        ];

        // Act
        $result = $this->mettaUsersService->createMettaUser($data);

        // Assert
        $this->assertInstanceOf(MettaUser::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
        $this->assertEquals('Test', $result->enFirstName);
        $this->assertDatabaseHas('metta_users', ['idUser' => $user->idUser]);
    }

    /**
     * Test createMettaUserFromUser creates metta user from user model
     */
    public function test_create_metta_user_from_user_creates_from_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $this->mettaUsersService->createMettaUserFromUser($user);

        // Assert
        $this->assertDatabaseHas('metta_users', ['idUser' => $user->idUser]);
    }

    /**
     * Test calculateProfileCompleteness calculates completeness
     */
    public function test_calculate_profile_completeness_calculates_percentage()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'John',
            'enLastName' => 'Doe',
            'email' => 'john@example.com'
        ]);

        // Act
        $result = $this->mettaUsersService->calculateProfileCompleteness($user->idUser);

        // Assert
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(0, $result);
        $this->assertLessThanOrEqual(100, $result);
    }

    /**
     * Test calculateProfileCompleteness returns 0 for non-existent
     */
    public function test_calculate_profile_completeness_returns_zero_for_nonexistent()
    {
        // Act
        $result = $this->mettaUsersService->calculateProfileCompleteness(99999);

        // Assert
        $this->assertEquals(0, $result);
    }
}
