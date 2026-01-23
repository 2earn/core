<?php

/**
 * Test Suite for NotificationsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\NotificationsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\settingsManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class NotificationsControllerTest extends TestCase
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
    public function test_index_returns_datatables()
    {
        $this->markTestSkipped('Requires settingsManager setup');
    }

    /** @test */
    public function test_index_returns_user_history()
    {
        $this->markTestSkipped('Requires history data');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
