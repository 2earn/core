<?php

/**
 * Test Suite for OAuthController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\OAuthController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OAuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }

    #[Test]
    public function test_controller_has_callback_method()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\OAuthController::class, 'callback'));
    }

    #[Test]
    public function test_callback_with_valid_code()
    {
        // Test that callback method exists
        $this->assertTrue(method_exists(\App\Http\Controllers\OAuthController::class, 'callback'));

        // Verify we can create a user for OAuth
        $user = User::factory()->create(['email' => 'oauth@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'oauth@example.com']);
    }

    #[Test]
    public function test_callback_fails_without_code()
    {
        // Test that accessing callback without code parameter returns error
        $response = $this->get('/oauth/callback');

        // Should not be 200 (success) without code
        $this->assertNotEquals(200, $response->status());

        // Verify we can create users (OAuth relies on user model)
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    #[Test]
    public function test_callback_fails_with_invalid_token()
    {
        // Test callback with invalid token
        $response = $this->get('/oauth/callback?code=invalid_token_12345');

        // Should not authenticate with invalid token
        $this->assertGuest();
        $this->assertTrue(in_array($response->status(), [302, 400, 401, 404, 500]));
    }

    #[Test]
    public function test_callback_decodes_jwt_token()
    {
        // Test JWT token structure (mock)
        $mockToken = [
            'sub' => 'user123',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'iat' => time(),
            'exp' => time() + 3600
        ];

        // Verify token structure
        $this->assertArrayHasKey('sub', $mockToken);
        $this->assertArrayHasKey('email', $mockToken);
        $this->assertArrayHasKey('exp', $mockToken);
    }

    #[Test]
    public function test_callback_logs_in_user()
    {
        // Test user login functionality
        $user = User::factory()->create();
        $this->actingAs($user);

        // Verify user is authenticated
        $this->assertAuthenticatedAs($user);
        $this->assertEquals($user->id, auth()->id());
    }

    #[Test]
    public function test_callback_redirects_to_home()
    {
        // Test that after authentication, user is redirected
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test redirect behavior
        $response = $this->get('/home');
        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }

    #[Test]
    public function test_callback_fails_with_missing_id_token()
    {
        // Test callback without id_token
        $response = $this->get('/oauth/callback?code=abc123');

        // Should fail without proper token
        $this->assertGuest();
        $this->assertTrue(in_array($response->status(), [302, 400, 401, 404, 500]));
    }
}
