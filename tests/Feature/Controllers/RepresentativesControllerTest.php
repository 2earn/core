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

    /** @test */
    public function test_index_returns_datatables()
    {
        $this->markTestSkipped('Requires RepresentativesService setup');
    }

    /** @test */
    public function test_index_returns_all_representatives()
    {
        $this->markTestSkipped('Requires representatives data');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
