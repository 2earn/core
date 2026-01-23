<?php

/**
 * Test Suite for DealsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\DealsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DealsControllerTest extends TestCase
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
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'create'));
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'store'));
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'show'));
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'update'));
        $this->assertTrue(method_exists(\App\Http\Controllers\DealsController::class, 'destroy'));
    }

    /** @test */
    public function test_user_factory_works()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }
}
