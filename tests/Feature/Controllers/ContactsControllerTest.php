<?php

/**
 * Test Suite for ContactsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\ContactsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactsControllerTest extends TestCase
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
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactsController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactsController::class, 'store'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactsController::class, 'update'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactsController::class, 'destroy'));
    }

    #[Test]
    public function test_user_instance_is_valid()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }
}
