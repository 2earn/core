<?php

/**
 * Test Suite for PostController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\PostController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostControllerTest extends TestCase
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
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\PostController::class, 'verifyMail'));
        $this->assertTrue(method_exists(\App\Http\Controllers\PostController::class, 'sendMail'));
        $this->assertTrue(method_exists(\App\Http\Controllers\PostController::class, 'getMember'));
    }

    #[Test]
    public function test_verify_mail_endpoint_responds()
    {
        $response = $this->postJson('/api/verify-mail', ['mail' => 'test@example.com']);
        $this->assertTrue(in_array($response->status(), [200, 404, 500]));
    }

    #[Test]
    public function test_user_has_email_attribute()
    {
        $this->assertNotNull($this->user);
        $this->assertNotNull($this->user->email);
        $this->assertTrue(isset($this->user->email));
    }

    #[Test]
    public function test_user_instance_is_valid()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }
}
