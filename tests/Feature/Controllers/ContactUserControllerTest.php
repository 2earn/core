<?php

/**
 * Test Suite for ContactUserController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\ContactUserController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\ContactUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactUserController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactUserController::class, 'store'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactUserController::class, 'update'));
        $this->assertTrue(method_exists(\App\Http\Controllers\ContactUserController::class, 'destroy'));
    }

    /** @test */
    public function test_user_instance_is_valid()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }
}
