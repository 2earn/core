<?php

/**
 * Test Suite for RequestController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\RequestController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\settingsManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class RequestControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $settingsManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->settingsManager = Mockery::mock(settingsManager::class);
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_settings_manager_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->settingsManager);
    }

    /** @test */
    public function test_controller_has_index_method()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\RequestController::class, 'index'));
    }

    /** @test */
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
