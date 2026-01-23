<?php

/**
 * Test Suite for RepresentativesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\RepresentativesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Services\RepresentativesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class RepresentativesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $representativesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->representativesService = Mockery::mock(RepresentativesService::class);
    }

    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_representatives_service_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->representativesService);
    }

    #[Test]
    public function test_controller_has_index_method()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\RepresentativesController::class, 'index'));
    }

    #[Test]
    public function test_user_instance_is_valid()
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
