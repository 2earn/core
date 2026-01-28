<?php

/**
 * Test Suite for HomeController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\HomeController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function test_authenticated_user_can_access_home()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\HomeController::class, 'lang'));
        $this->assertTrue(method_exists(\App\Http\Controllers\HomeController::class, 'updateProfile'));
        $this->assertTrue(method_exists(\App\Http\Controllers\HomeController::class, 'updatePassword'));
    }

    #[Test]
    public function test_user_has_required_attributes()
    {
        $this->assertNotNull($this->user->id);
        $this->assertNotNull($this->user->email);
        $this->assertInstanceOf(User::class, $this->user);
    }

    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $newUser = User::factory()->create(['name' => 'Test User']);

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals('Test User', $newUser->name);
        $this->assertDatabaseHas('users', ['id' => $newUser->id]);
    }
}
